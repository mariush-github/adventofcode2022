<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

$map = [];
$minx=1024;
$maxx=0;
$maxy=0;
$bottomy=0;

$lines = explode(LF,trim(file_get_contents(__DIR__.'/inputs/input14.txt'),LF));
foreach ($lines as $line){
	$points = explode('->',$line);
	list($sx,$sy) = explode(',',trim($points[0]));
	for ($i=1;$i<count($points);$i++){
		list($fx,$fy) = explode(',',trim($points[$i]));
		$orientation = ($sy==$fy) ? 'h' : 'v';
		$direction = ($orientation=='h') ?  ( ($sx<$fx) ? 1 : -1 )  :  ( ($sy<$fy) ? 1 : -1 );
		if ($orientation=='h') {
			for ($j=$sx;$j!=$fx;$j=$j+$direction) { $map[$j.','.$sy] = '#'; } 
		}
		if ($orientation=='v') {
			for ($j=$sy;$j!=$fy;$j=$j+$direction) { $map[$sx.','.$j] = '#'; } 
		}
		$map[$sx.','.$sy] = '#';
		$map[$fx.','.$fy] = '#';
		$sx=$fx;
		$sy=$fy;
	}
}
foreach ($map as $idx=>$value) {
	$values = explode(',',$idx);
	$x = intval($values[0]);
	$y = intval($values[1]);
	if ($minx>$x)$minx=$x;
	if ($maxx<$x)$maxx=$x;
	if ($maxy<$y)$maxy=$y;
}
$minx--;
$maxx++;
var_dump($minx,$maxx,$maxy);

function addball($mode=1) {
	global $minx;
	global $maxx;
	global $maxy;
	global $map;
	$bx=500;
	$by=0;
	while (1) {
		if ($by==$maxy) return [$bx,$by];
		
		$ol = ($bx-1).','.($by+1);
		$od = ($bx  ).','.($by+1);
		$or = ($bx+1).','.($by+1);
		$left = isset($map[$ol]) ? $map[$ol] : '.';
		$down = isset($map[$od]) ? $map[$od] : '.';
		$right= isset($map[$or]) ? $map[$or] : '.';
		if (($mode==2) && (($by+1)==$maxy)) {
			$left = '#';
			$down = '#';
			$right= '#';
		}
		if (($left!='.') && ($down!='.') && ($right!='.')) {
			$map[$bx.','.$by]= 'o';
			return [$bx,$by];
		}
		if ($down=='.') {
			$by++;
		} else {
			if ($left=='.') {
				$bx--;$by++;
			} else {
				$bx++;$by++;
			}
		}
	}
}
$score = 0;
$continue=true;
while ($continue) {
	$result =addball();
	if ($result[1]==$maxy) $continue=false;
	if ($result[1]!=$maxy) $score++;
}

for ($j=0;$j<=$maxy;$j++ ){
	echo str_pad($j,3,' ',STR_PAD_LEFT).' ';
	for ($i=$minx;$i<=$maxx;$i++) echo isset($map[$i.','.$j])==true ? $map[$i.','.$j] : '.';
	echo "\n";
}
echo "\n";
echo "solution 01 = ".$score."\n";
$maxy=$maxy+2;
$continue=true;
while ($continue) {
	$result =addball(2);
	if (($result[1]==0) &&($result[0]==500)) $continue=false;
	$score++;
}
for ($j=0;$j<=$maxy;$j++ ){
	echo str_pad($j,3,' ',STR_PAD_LEFT).' ';
	for ($i=$minx;$i<=$maxx;$i++) echo isset($map[$i.','.$j])==true ? $map[$i.','.$j] : '.';
	echo "\n";
}
echo "\n";
echo "solution 02 = ".$score."\n";

?>