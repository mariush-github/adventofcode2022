<?php
define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));
$content = trim(file_get_contents(__DIR__.'/inputs/input04.txt'),CRLF.' ');
$content = str_replace('-',',',$content);
$lines = explode(LF,$content);

$score1 = 0;
$score2 = 0;
foreach ($lines as $idx => $line) {
	$values = explode(',',$line);
	$values[0] = intval(trim($values[0]));
	$values[1] = intval(trim($values[1]));
	$values[2] = intval(trim($values[2]));
	$values[3] = intval(trim($values[3]));
	$within = false;
	$overlap = false;
	if (($values[0]>=$values[2]) && ($values[1]<=$values[3])) $within = true;
	if (($values[2]>=$values[0]) && ($values[3]<=$values[1])) $within = true;
	$overlap = $within;
	if ($overlap==false) {
		if (($values[0]>=$values[2]) && ($values[0]<=$values[3])) $overlap = true;
		if (($values[2]>=$values[0]) && ($values[2]<=$values[1])) $overlap = true;
		if (($values[1]>=$values[2]) && ($values[1]<=$values[3])) $overlap = true;
		if (($values[3]>=$values[0]) && ($values[3]<=$values[1])) $overlap = true;
	}
	if ($within) $score1++;
	if ($overlap)$score2++;
	
}
echo "solution 01 = $score1 \n";
echo "solution 01 = $score2 \n";
die();
$score = 0;

for ($i=0;$i<count($lines);$i=$i+3) {
	
	$smallest = $lines[$i];
	$smallest = strlen($lines[$i+1])<strlen($smallest) ? $lines[$i+1] : $smallest;
	$smallest = strlen($lines[$i+2])<strlen($smallest) ? $lines[$i+2] : $smallest;
	$common ='';
	for ($j=0;$j<strlen($smallest);$j++) {
		$c = substr($smallest,$j,1);
		if ((strpos($lines[$i],$c)!==FALSE) && (strpos($lines[$i+1],$c)!==FALSE) && (strpos($lines[$i+2],$c)!==FALSE)) {
			$common = $c;
		}
	}
	$v = ord($common);
	$v = $v<0x5B ? ($v-0x41+27) : ($v-0x61+1);
	$score +=$v;
	
}
echo "solution 02 = $score \n";

?>