<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

function runme($mode = 0) {
	$input_lines = explode(LF,trim(file_get_contents(__DIR__.'/inputs/input05.txt'),LF));
	$stacks = ['','','','','','','','','',''];
	$i=-1;
	$line='.';
	while (trim($line)!='') {
		$i++;
		$line = $input_lines[$i];
		$stack_count = intdiv(strlen($line)+1,4);
		for ($j=0;$j<$stack_count;$j++) {
			$c = substr($line,$j*4+1,1);
			$stacks[$j+1] .= ($c!=' ') ? $c : '';
		}
	}
	$command_offset  = $i+1;
	// var_dump($stacks);
	// var_dump($command_offset);
	// var_dump(count($input_lines));

	for ($i=$command_offset;$i<count($input_lines);$i++) {
		$line = trim($input_lines[$i]);
		$parts = explode(' ',$line);
		$counter = intval($parts[1]);
		$stack_from = intval($parts[3]);
		$stack_to   = intval($parts[5]);
		
		if ($mode==0) {
			for ($j = 0;$j<$counter;$j++) {
				$c = substr($stacks[$stack_from],0,1);
				$stacks[$stack_from] = substr($stacks[$stack_from],1);
				$stacks[$stack_to  ] = $c.$stacks[$stack_to];
			}
		} else {
			$c = substr($stacks[$stack_from],0,$counter);
			$stacks[$stack_from] = substr($stacks[$stack_from],$counter);
			$stacks[$stack_to] = $c.$stacks[$stack_to];
		}
	}

	$s = '';
	for ($i=1;$i<count($stacks);$i++) {
		$s.=substr($stacks[$i],0,1);
	}
	return $s;
}
echo "score 01 = ".runme(0)."\n";
echo "score 02 = ".runme(1)."\n";
?>