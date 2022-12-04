<?php
define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));
$content = trim(file_get_contents(__DIR__.'/inputs/input03.txt'),CRLF.' ');
$lines = explode(LF,$content);

$score = 0;
foreach ($lines as $idx => $line) {
	$linelen = strlen($line);
	$a = substr($line,0,intdiv($linelen,2));
	$b = substr($line,intdiv($linelen,2));
	for ($i=0;$i<strlen($a);$i++) {
		$c = substr($a,$i,1);
		$v = ord($c);
		$v = $v<0x5B ? ($v-0x41+27) : ($v-0x61+1);
		$found = strpos($b,$c);
		if ($found!==FALSE) {
			$score += $v;
			$a =str_replace($c,'',$a);
			$b =str_replace($c,'',$b);
		}
	}
}
echo "solution 01 = $score \n";

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