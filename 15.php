<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));


$sx=$sy=$fx=$fy=0;
$lines = explode(LF,trim(file_get_contents(__DIR__.'/inputs/input15.txt'),LF));
$map = [];
$sensors = [];

$threshold = 10; //2000000; // set this to 2000000 for full test

foreach ($lines as $idx=>$line) {
	// Sensor at x=2, y=18: closest beacon is at x=-2, y=15
	$lines[$idx] = str_replace(['Sensor at ',': closest beacon is at','x=','y=',' '],['',',','','',''],$line);
	//echo $lines[$idx]."\n";
	list($sx,$sy,$fx,$fy) = explode(',',$lines[$idx]);
	$map[$sx.','.$sy]='S';
	$map[$fx.','.$fy]='B';
	$distance   = 0;
	$distance  += ($sx<$fx) ? ($fx-$sx) : ($sx-$fx);
	$distance += ($sy<$fy) ? ($fy-$sy) : ($sy-$fy);
	array_push($sensors,array('sx'=>$sx,'sy'=>$sy,'fx'=>$fx,'fy'=>$fy,'d'=>$distance));
}


$minx=4000000;
$maxx=0;
$miny=4000000;
$maxy=0;

foreach ($sensors as $sensor) {
	$sx = $sensor['sx'];
	$sy = $sensor['sy'];
	$d  = $sensor['d' ];
	// determine the mins and max for part 2
	$xmin  = $sx-$d;
	$xmax  = $sx+$d;
	if ($minx>$xmin) $minx=$xmin;
	if ($maxx<$xmax) $maxx=$xmax;
	$ymin  = $sy-$d;
	$ymax  = $sy+$d;
	if ($miny>$ymin) $miny=$ymin;
	if ($maxy<$ymax) $maxy=$ymax;
	
	if ( (($sy-$d)<=$threshold) && (($sy+$d)>=$threshold) ) {
		//for ($j=$sy-$d;$j<=$sy+$d;$j++) {
			$j=$threshold;
			for ($i=$sx-$d;$i<=$sx+$d;$i++) {
				$c = isset($map[$i.','.$j])==true ? $map[$i.','.$j] : '.';
				if (($c!='S') && ($c!='B')) {
					$dd = 0;
					$dd += ($sx<$i) ? ($i-$sx) : ($sx-$i);
					$dd += ($sy<$j) ? ($j-$sy) : ($sy-$j);
					if ($dd<=$d) $map[$i.','.$j] = '#';
				}
			}
		//}
	}
}
$score=0;
foreach ($map as $idx=>$value) {
	if ($value=='#') {
		list($x,$y)=explode(',',$idx);
		if ($y==$threshold) $score++;
	}
}
echo "Solution 01 = ".$score."\n";
echo "original range:  $minx x $miny and $maxx x $maxy\n";

if ($minx<0) $minx=0;
if ($miny<0) $miny=0;
if ($maxx>4000000) $maxx=4000000;
if ($maxy>4000000) $maxy=4000000;
// $maxx=20; // uncomment for test case
// $maxy=20;
echo "searching for beacon between coordinates $minx x $miny and $maxx x $maxy\n";
for ($j=$miny;$j<=$maxy;$j++) {
	for ($i=0;$i<=$maxx;$i++) {
		//echo "check $i $j ";
		$found = false;
		$jumpto = $i;
		foreach ($sensors as $sensor) {
			if ($found==false) {
				if ( (($sensor['sy']-$sensor['d'])<=$j) || (($sensor['sy']+$sensor['d'])<=$j)) {
					
					// sensor's "beam" hits or crosses the Y line, so let's figure the horizontal range 
					$verticalDistance = ($j<$sensor['sy']) ? $sensor['sy']-$j : $j-$sensor['sy'];
					
					$range = [$sensor['sx']-$sensor['d']+$verticalDistance,$sensor['sx']+$sensor['d']-$verticalDistance];
					//echo "sensor x=".$sensor['sx'].' y='.$sensor['sy'].' d='.$sensor['d'].' v='.$verticalDistance.' range='.$range[0].':'.$range[1]."\n";
					if (($i>=$range[0]) && ($i<=$range[1])) {
						//echo "sensor x=".$sensor['sx'].'x'.$sensor['sy']." hits line $j with x range ".$range[0]."-".$range[1].", moving i to ".($range[1]+1)."\n";
						$jumpto= $range[1];
						$found=true;
					}
				}
			}
		}
		if ($found==false) {
			$score = $i *4000000 + $j;
			echo "\n no solution found for $i x $j score=$score\n";
			die();
		}
		$i=$jumpto;
	}
	if (($j & 0xFFFF)==0xFFFF) echo ".";
}
die();


?>