<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

$max_y = 99;
$max_x = 99;
$grid = str_split(str_replace(LF,'',file_get_contents(__DIR__.'/inputs/input08.txt')));

foreach ($grid as $index =>$value) { $grid[$index] = intval($value); }
// could use 8 bits for each tree, 4 bits for visibility (left,right,top,bottom) and 4 bits for value (0-9)
// but since memory efficiency is not a factor and php uses 32 or 64 bits per value anyway, just use the bits 9-12
// visibility +256,+512,+1024,+2048 =  bottom,top,right, left)
for ($y = 0; $y<$max_y;$y++) {
	$tree = &$grid[$y*$max_y]; 
	$tree += 2048;
	$max = $tree & 0xFF;
	for ($x=1;$x<$max_x;$x++) {
		$tree = &$grid[$y*99+$x]; $value = $tree & 0xFF;
		if ($value>$max) { $tree +=2048; $max=$value; }
	}
	$tree = &$grid[$y*$max_x+$max_x-1]; 
	$tree += 1024;
	$max = $tree & 0xFF;
	for ($x=$max_x-2;$x>=0;$x--) {
		$tree = &$grid[$y*$max_x+$x]; $value = $tree & 0xFF;
		if ($value>$max) { $tree +=1024; $max=$value; }
	}
}

for ($x = 0; $x<$max_x;$x++) {
	$tree = &$grid[$x]; 
	$tree += 512;
	$max = $tree & 0xFF;
	for ($y=1;$y<$max_y;$y++) {
		$tree = &$grid[$y*$max_x+$x]; $value = $tree & 0xFF;
		if ($value>$max) { $tree +=512; $max=$value; }
	}
	$tree = &$grid[$max_x*($max_y-1)+$x]; 
	$tree += 256;
	$max = $tree & 0xFF;
	for ($y=$max_y-2;$y>=0;$y--) {
		$tree = &$grid[$y*$max_y+$x]; $value = $tree & 0xFF;
		if ($value>$max) { $tree +=256; $max=$value; }
	}
}
$score = 0;
foreach($grid as $index=>$value) {$score += ($value<256)? 0 : 1;}
echo "solution 01 = $score\n";
$scenic = [];
for ($i=0;$i<$max_y*$max_x;$i++) $scenic[$i]=0;


for ($y=1;$y<$max_y-1;$y++) {
 for ($x=1;$x<$max_x-1;$x++) {
	 $scenic[$y*$max_x + $x ] = calculate_scenic_score($x,$y);
 }
}
rsort($scenic,SORT_NUMERIC);

echo "solution 01 = ".$scenic[0]."\n";


function calculate_scenic_score($x,$y) {
	global $grid;
	global $max_x;
	global $max_y;
	
	$tree_value = $grid[$y*$max_x+$x] & 0xFF;
	$left = 0; 
	$right = 0;
	$up = 0;
	$down = 0;
	$i=$x-1; $continue=true; 
	while ($continue==true) {
		$value = $grid[$y*$max_x+$i] & 0xFF;
		$left++;
		if ($value>=$tree_value) $continue=false;
		if ($i==0) $continue=false;
		$i--;
	}
	$i=$x+1; $continue=true; 
	while ($continue==true) {
		$value = $grid[$y*$max_x+$i] & 0xFF;
		$right++;
		if ($value>=$tree_value) $continue=false;
		if ($i==$max_x-1) $continue=false;
		$i++;
	}
	$i=$y-1; $continue=true; 
	while ($continue==true) {
		$value = $grid[$i*$max_x+$x] & 0xFF;
		$up++;
		if ($value>=$tree_value) $continue=false;
		if ($i==0) $continue=false;
		$i--;
	}
	$i=$y+1; $continue=true; 
	while ($continue==true) {
		$value = $grid[$i*$max_x+$x] & 0xFF;
		$down++;
		if ($value>=$tree_value) $continue=false;
		if ($i==$max_y-1) $continue=false;
		$i++;
	}
	return $left*$right*$up*$down;
}
?>