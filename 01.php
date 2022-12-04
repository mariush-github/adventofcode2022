<?php
define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));
$content = trim(file_get_contents(__DIR__.'/inputs/input01.txt'),chr(0x0D).chr(0x0A).' ');
$lines = explode(LF,$content);
$elves = [];
$totals = [];
$elves[0]=[];
$totals[0] = 0;

$elvesCnt = 0;
$max = 0;
foreach ($lines as $idx => $line) {
	if (trim($line)=='') {
		if ($max<$totals[$elvesCnt]) $max = $totals[$elvesCnt];
		$elvesCnt++;
		$elves[$elvesCnt]=[];
		$totals[$elvesCnt] = 0;
	} else {
		$value = intval($line);
		array_push($elves[$elvesCnt],$value);
		$totals[$elvesCnt]+= $value;
	}
}

echo "solution 01 = $max \n";
rsort($totals,SORT_NUMERIC);
echo "solution 02 = ". ($totals[0]+$totals[1]+$totals[2])."\n";
?>