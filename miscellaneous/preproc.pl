#!/usr/bin/perl -w

use XML::LibXML;

## BEGIN OPEN FILE AND READ AS XML
my $corpus = do {
    local $/ = undef;
    open ($in, '<', "Corpse.txt") or die $!;
    my $original = <$in>;
    $original =~ s/<ID>/<DOC><ID>/g;
    $original =~ s/<\/LINK>/<\/LINK><\/DOC>/g;
	$original =~ s/[\+\|\$#@~!&*()\[\];.,:?^`\\-]+/ /g;
    $original;
};
close $in;
open ($in, '>', "newcor.txt") or die $!;
print $in $corpus;
close $in;

my $xml_corpus = XML::LibXML->load_xml(string => $corpus);
my @docs = $xml_corpus->findnodes('/DOCS/DOC');
my @tf_paket;
my @tf_isi;
my @tf_comb;
my %df;
my %ids_word;
my $counter = 0;
my $id_counter = 0;

for my $doc (@docs) {
	my %this_isi;
	my %this_comb;

	my $paket = $doc->findvalue('ISI');
	my @words = split(/\s+/, $paket);
	for my $word (@words) {
		$word = lc($word);
		if (exists($this_isi{$word})) {
			$this_isi{$word}++;
			$this_comb{$word}++;
		} else {
			$this_isi{$word} = 1;
			$this_comb{$word} = 1;
		}
	}
	$tf_isi[$counter] = \%this_isi;

	my %this_paket;

	$paket = $doc->findvalue('PAKET');
	@words = split(/\s+/, $paket);
	for my $word (@words) {
		$word = lc($word);
		if (exists($this_paket{$word})) {
			$this_paket{$word}++;
		} else {
			$this_paket{$word} = 1;
		}

		if (exists($this_comb{$word})) {
			$this_comb{$word}++;
		} else {
			$this_comb{$word} = 1;
		}
	}
	$tf_paket[$counter] = \%this_paket;
	$tf_comb[$counter] = \%this_comb;

	for $key (keys(%this_comb)) {
		if (exists($df{$key})) {
			$df{$key}++;
		} else {
			$df{$key} = 1;
		}

		if (!exists($ids_word{$key})) {
			$ids_word{$key} = $id_counter++;
		}
	}
	$counter++;
}

##### PRINT TABLE words #####
open ($in, '>', "input_words.txt") or die $!;
print $in "INSERT INTO words VALUES\n";
my $has_next = scalar keys(%ids_word);
print "\n$has_next";
for $key (keys(%ids_word)) {
	print $in "( $ids_word{$key} , '$key' )";
	$has_next--;
	if ($has_next) {
		print $in ",\n";
	} else {
		print $in ";\n\n\n";
	}
}
close $in;
##### END PRINT TABLE words ######

##### PRINT TABLE idf_table #####
open ($in, '>', "input_idf_table.txt") or die $!;
print $in "INSERT INTO idf_table VALUES\n";
$has_next = scalar keys(%df);
print "\n$has_next";
for $key (keys(%df)) {
	$df{$key} = log10($counter/$df{$key});
	print $in "( $ids_word{$key} , $df{$key} )";
	$has_next--;
	if ($has_next) {
		print $in ",\n";
	} else {
		print $in ";";
	}
}
close $in;
##### END PRINT TABLE idf_table #####

##### PRINT TABLE document_words #####
open ($in, '>', "input_document_words.txt") or die $!;
print $in "INSERT INTO document_words VALUES\n";

$counter--;
for (; $counter >= 0; $counter--) {
	for $key (keys(%{$tf_comb[$counter]})) {
		my $the_id = $ids_word{$key};
		my $the_tf = @{$tf_comb[$counter]}{$key};
		my $the_weight = $the_tf * $df{$key};
		print $in "( $counter , $the_id , $the_tf , $the_weight ), ";
	}
	if (!$counter == 0) {
		print $in ",\n";
	} else {
		print $in ";";
	}
}
close $in;
##### END PRINT TABLE document_words #####

sub log10 {
    my $n = shift;
    return log($n)/log(10);
}