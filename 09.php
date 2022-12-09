<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

// set to true to show pretty ascii drawing for debugging purposes
$draw_map = false;
$draw_map_level = 1; // 1 = less verbose, less graphs. 2 to see after EVERY step, 1 to see only after a line is done

//test 1, comment out the line below to run the test
/*
$moves = explode(CRLF,'R 4
U 4
L 3
D 1
R 4
D 1
L 5
R 2');
*/
//test 2, for second part, comment out to run the test
/*
$moves = explode(CRLF,'R 5
U 8
L 8
D 3
R 17
D 10
L 25
U 20');
*/
$moves = explode(LF,trim(file_get_contents(__DIR__.'/inputs/input09.txt'),LF));


$nodes = [];
$history = [];
$nodeCount = 2;

run_moves(2);

$score = 0;
foreach ($history as $h =>$v) {
	if (($h[0]=='#')) $score++;
}
echo "solution 01 = $score \n";

if ($draw_map==true) {
	echo "waiting 5 seconds to see the score... press PAUSE if desired.\n";
	sleep(5);
}
run_moves(10);

$score = 0;
foreach ($history as $h =>$v) {
	if (($h[0]=='#')) $score++;
}
echo "solution 01 = $score \n";

die();



function run_moves($nodeCount=2){
	global $history;
	global $nodes;
	global $moves;
	global $draw_map;
	global $draw_map_level;
	$history = [];
	$nodes = [];
	
	for ($i=0;$i<$nodeCount;$i++) { $nodes[$i]  = [128,128]; $history[$i.'128,128'] = 1;}
	$history['#128,128']=1;
	$k = 0;
	foreach ($moves as $move) {
		list($direction,$count) = explode(' ',$move);
		$count = intval($count);
		$change_head = [0,0];
		if ($direction=='U') $change_head = [0,-1];
		if ($direction=='D') $change_head = [0,+1];
		if ($direction=='L') $change_head = [-1,0];
		if ($direction=='R') $change_head = [+1,0];
			for ($i=1;$i<=$count;$i++) {
				if ($draw_map==true) echo "$direction $count ($i):\n";
				$previous = $nodes[0];
				list($x,$y) = $nodes[0];
				$nodes[0][0] += $change_head[0];
				$nodes[0][1] += $change_head[1];
				unset($history['0'.$x.','.$y]);
				list($x,$y) = $nodes[0];
				$history['0'.$x.','.$y] = 1;

				for ($j=1;$j<$nodeCount;$j++) {
					$touching = false;
					list($m,$n) = $nodes[$j-1];
					list($x,$y) = $nodes[$j];
					
					$change = [0,0];
					$dx=abs($x-$m);
					$dy=abs($y-$n);
					if (($dx<2)&&($dy<2)) $touching=true;

					if ($touching==false) {
						$change = [0,0];
						$calculated = false;
						if ($y==$n) { $change = [(($x>$m) ? -1 : +1),0]; $calculated = true; }
						if ($x==$m) { $change = [0, (($y>$n) ? -1 : +1)]; $calculated = true; }
						if ($calculated==false) {
							
							// n+0  H   H    . 
							// n+1 . .  ..  ..H   H..
							// n+2 T.   .T  T..   ..T   
							if (($x==($m-1)) && ($y==($n+2))) $change = [+1,-1];
							if (($x==($m+1)) && ($y==($n+2))) $change = [-1,-1];
							if (($x==($m-2)) && ($y==($n+1))) $change = [+1,-1];
							if (($x==($m+2)) && ($y==($n+1))) $change = [-1,-1];
							// n+0  T   T    . 
							// n+1 . .  ..  ..T   T..
							// n+2 H.   .H  H..   ..H  
							
							if (($x==($m+1)) && ($y==($n-2))) $change = [-1,+1];
							if (($x==($m-1)) && ($y==($n-2))) $change = [+1,+1];
							
							if (($x==($m+2)) && ($y==($n-1))) $change = [-1,+1];
							if (($x==($m-2)) && ($y==($n-1))) $change = [+1,+1];
							
							if (($x==($m-2)) && ($y==($n+2))) $change = [+1,-1];
							if (($x==($m+2)) && ($y==($n+2))) $change = [-1,-1];
							
							if (($x==($m-2)) && ($y==($n-2))) $change = [+1,+1];
							if (($x==($m+2)) && ($y==($n-2))) $change = [-1,+1];
							
							$calculated=true;
						}
						unset($history[$j.$x.','.$y]);
						
						$nodes[$j][0] += $change[0];
						$nodes[$j][1] += $change[1];
						list($x,$y) = $nodes[$j];
						$history[$j.$x.','.$y]=1;
						if ($j==$nodeCount-1) $history['#'.$x.','.$y]=1;
					}
				}
				if (($draw_map==true) && ($draw_map_level==2)) draw_moves($nodeCount);
			}
		 if ($draw_map==true) draw_moves($nodeCount);
	}
}


function draw_moves($nodes=2){
	global $history;
	$min_x = 0xFFFF;
	$max_x = -0xFFFF;
	$min_y = 0xFFFF;
	$max_y = -0xFFFF;
	foreach ($history as $h => $v) {
		$type = substr($h,0,1);
		list($x,$y) = explode(',',substr($h,1));
		$x=intval($x);$y=intval($y);
		if ($max_x<$x) $max_x = $x;
		if ($min_x>$x) $min_x = $x;
		if ($max_y<$y) $max_y = $y;
		if ($min_y>$y) $min_y = $y;
	}
	for ($y=$min_y;$y<=$max_y;$y++) {
		for ($x=$min_x;$x<=$max_x;$x++){
			$char = '.';
			if (isset($history['0'.$x.','.$y])==true) $char = 'H';
			if (isset($history['#'.$x.','.$y])==true) $char = '#';
			for ($i=$nodes-1;$i>=0;$i--) {
				if (isset($history[$i.$x.','.$y])==true) $char = chr($i+48);
			}
			echo $char;
		}
		echo "\n";
	}
	echo "\n";
}
?>