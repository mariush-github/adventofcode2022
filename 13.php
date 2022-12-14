<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

function validate($input_a,$input_b) {
	$offseta = 0;
	$offsetb = 0;
	$a = json_decode($input_a);
	$b = json_decode($input_b);
	if (is_int($a) && is_int($b)) return $a <=> $b; 
	
	if (is_int($a) && is_array($b)) $a=[$a];
	if (is_int($b) && is_array($a)) $b=[$b];
	
	while ((count($a)>0) && (count($b)>0)) {
		$aa = array_shift($a);
		$bb = array_shift($b);
		$result = validate(json_encode($aa),json_encode($bb));
		if ($result) return $result;
	}
	return (count($a)-count($b));
}



$list = explode(LF.LF,file_get_contents(__DIR__.'/inputs/input13.txt'));
$i = 1;
$score =0;
foreach ($list as $li) {
	$lines = explode(LF,$li);
	$result = validate($lines[0],$lines[1]);
	if ($result<1) $score+= $i;
	$i++;
}

echo "solution 01 = $score\n";

$packets = [];
foreach ($list as $li) {
	$lines = explode(LF,$li);
	array_push($packets,$lines[0]);
	array_push($packets,$lines[1]);
}
array_push($packets,'[[2]]');
array_push($packets,'[[6]]');
usort($packets,'validate');
$first = array_search("[[2]]", $packets)+1;
$second = array_search("[[6]]", $packets)+1;
$score = $first * $second;
echo "solution 02 = $score ($first x $second)\n";
?>