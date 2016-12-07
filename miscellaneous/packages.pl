open ($in, '<', "Corpse_unaltered.txt") or die $!;
@pakets;
@isis;
@hargas;
@links;

$reading_isi = 0;
$counter = 1;
$anu = "";

# Iterate through corpus - storing pakets, isis, hargas, links
while ($input = <$in>) {
	if ($reading_isi) {
		if ($input =~ /(.*)<\/ISI>/) {
			$anu .= $1;
			$isis[$counter] = $anu;
			$reading_isi = 0;
		} else {
			$anu .= $input;
		}
	}

	if ($input =~ /<PAKET>(.*)<\/PAKET>/) {
		$pakets[$counter] = $1;
		next;
	}

	if ($input =~ /<ISI>(.*)<\/ISI>/) {
		$isis[$counter] = $1;
		next;
	}

	if ($input =~ /<ISI>(.*)/) {
		$anu = $1;
		$reading_isi = 1;
		next;
	}

	if ($input =~ /<HARGA>(.*)<\/HARGA>/) {
		$hargas[$counter] = $1;
		next;
	}

	if ($input =~ /<LINK>(.*)<\/LINK>/) {
		$links[$counter++] = $1;
		next;
	}
}
close $in;

# Checks through arrays, prints empty values
# WARNING: PACKAGE 14 HAS NO LINK
for ($i = 1; $i <= 85; $i++) {
	if ($pakets[$i] eq "") {
		print "Missing paket on index $i!\n";
	}

	if ($isis[$i] eq "") {
		print "Missing isi on index $i!\n";
	}

	if ($hargas[$i] eq "") {
		print "Missing harga on index $i!\n";
	}

	if ($links[$i] eq "") {
		print "Missing link on index $i!\n";
	}
}

# Printing standard query, compliant with current system
open ($in, '>', "input_packages.txt") or die $!;
print $in "INSERT INTO packages VALUES\n";
for ($i = 1; $i <= 85; $i++) {
	print $in "( $i , '$pakets[$i]' , '$isis[$i]' , '$hargas[$i]' )";
	if ($i != 85) {
		print $in ",\n";
	} else {
		print $in ";";
	}
}
close $in;

# Printing expanded query, including link
open ($in, '>', "input_packages_w_link.txt") or die $!;
print $in "INSERT INTO packages VALUES\n";
for ($i = 1; $i <= 85; $i++) {
	print $in "( $i , '$pakets[$i]' , '$isis[$i]' , '$hargas[$i]' , '$links[$i]' )";
	if ($i != 85) {
		print $in ",\n";
	} else {
		print $in ";";
	}
}
close $in;