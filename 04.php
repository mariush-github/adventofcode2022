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
?>
