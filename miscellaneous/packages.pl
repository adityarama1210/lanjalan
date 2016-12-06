open ($in, '<', "Corpse_unaltered.txt") or die $!;
@ids;
@pakets;
@isis;
@hargas;

$reading_isi = 0;
$counter = 1;
$anu = "";
while ($input = <$in>) {
	if ($reading_isi) {
		if ($input =~ /(.+)<\/ISI>/) {
			$anu += $input;
			print "\n hehe $anu";
			$isis[$counter] = $anu;
			$reading_isi = 0;
		} else {
			$anu += $input;
		}
	}

	if ($input =~ /<PAKET>(.+)<\/PAKET>/) {
		$anu = $1;
		$pakets[$counter] = $anu;
	}

	if ($input =~ /<ISI>(.+)<\/ISI>/) {
		$anu = $1;
		$pakets[$counter] = $anu;
	}

	if ($input =~ /<ISI>(.+)/) {
		$anu = $1;
		$reading_isi = 1;
	}

	if ($input =~ /<HARGA>(.+)<\/HARGA>/) {
		$anu = $1;
		$hargas[$counter] = $anu;
		$counter++;
	}
}

print $pakets[85];
print "\n$isis[2]";
print $hargas[85];