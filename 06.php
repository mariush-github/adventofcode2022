<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0D));

$data = trim(file_get_contents(__DIR__.'/inputs/input06.txt',LF));

$position1 = -1;
$position2 = -1;
for ($i=4;$i<strlen($data);$i++) {
	$map = []; for ($j=0;$j<4;$j++) { $map[substr($data,$i-$j-1,1)] = 1; }
	if ((count($map)==4) && ($position1==-1)) { $position1 = $i; }
	if ($i>=14) {
		for ($j=4;$j<14;$j++) { $map[substr($data,$i-$j-1,1)] = 1; }
		if (count($map)==14) { $position2 = $i;} 
	}
	if (($position1!=-1) && ($position2!=-1)) { break;}
}
echo "score 01 = $position1\n";
echo "score 02 = $position2\n";
?>