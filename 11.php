<?php
include __DIR__.'/libs/BigInteger.php';

use PHP\Math\BigInteger\BigInteger;

// change to 2 to get answer to second part
$solution = 1;
$debug = 0; // set to 1 to display steps on screen

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

$dataset = explode(LF.LF,trim(file_get_contents(__DIR__.'/inputs/input11.txt'),LF));
$monkeys=[];
$globalModulus = 1;

foreach ($dataset as $idx=>$data) {
	$lines = explode(LF,$data);
	$lines[1]= str_replace(['Starting items:',' '],['',''],$lines[1]);
	$items = explode(',',$lines[1]);
	foreach ($items as $idx=>$v) {
		$items[$idx] = new BigInteger(trim($v));
	}
	$lines[2] = str_replace('Operation: new = old ','',$lines[2]);
	list($operation,$value) = explode(' ',trim($lines[2]));
	$value = ($value=='old') ? 0 : intval($value);
	$lines[3] = str_replace('Test: divisible by ','',$lines[3]);
	$divisible = intval(trim($lines[3]));
	$lines[4] = str_replace('If true: throw to monkey ','',$lines[4]);
	$if_true = intval(trim($lines[4]));
	$lines[5] = str_replace('If false: throw to monkey ','',$lines[5]);
	$if_false = intval(trim($lines[5]));
	
	$globalModulus = $globalModulus*$divisible;
	
	array_push($monkeys, array('items'=>$items,'op'=>$operation,'value'=>$value,'div'=>$divisible,'true'=>$if_true,'false'=>$if_false,'inspections'=>0));
}

$temp = new BigInteger("0");

for ($round=1;$round<10001;$round++) {
	for ($m = 0;$m<count($monkeys);$m++) {
		if ($debug==1) echo "Monkey $m : \n";
		$micount = count($monkeys[$m]['items']);
		for ($micounter=0;$micounter<$micount;$micounter++) {
			$mitem = array_shift($monkeys[$m]['items']);
			$temp->setValue($mitem->getValue());
			$temp->divide($globalModulus);
			$temp->multiply($globalModulus);
			$mitem->subtract($temp->getValue());
			
			if ($debug==1) echo "item ".$mitem->getValue()."\n";
			if ($debug==1) echo "operation = ".$monkeys[$m]['op']." value = ".$monkeys[$m]['value'];
			$value = new BigInteger(intval($monkeys[$m]['value']));
			if ($value->getValue()==0) $value->setValue($mitem->getValue());
			if ($monkeys[$m]['op']=='*') $mitem->multiply($value->getValue()); // = $mitem * $value;
			if ($monkeys[$m]['op']=='+') $mitem->add($value->getValue());// = $mitem + $value;
			if ($debug==1) echo " new level = ".$mitem->getValue()."\n";
			if ($solution==1) $mitem->divide(3);
			if ($debug==1) echo " bored level = ".$mitem->getValue()."\n";
			$mdiv = intval($monkeys[$m]['div']);
			$is_div = false;
			$temp->setValue($mitem->getValue());
			$temp->divide($mdiv);
			$temp->multiply($mdiv);
			if ($mitem->getValue() == $temp->getValue()) $is_div=true;
			if ($debug==1) echo "Current worry level is ".(($is_div==true) ? '' : 'NOT')." divisible by $mdiv.\n";
			$move_true = intval($monkeys[$m]['true']);
			$move_false = intval($monkeys[$m]['false']);
			if ($debug==1) echo "Item is thrown to monkey ".(($is_div==true) ? $move_true : $move_false)."\n";
			if ($is_div==true) array_push($monkeys[$move_true]['items'],$mitem);
			if ($is_div==false) array_push($monkeys[$move_false]['items'],$mitem);
			$monkeys[$m]['inspections']++;
		}
	}
	if ($round % 1000 ==0) echo $round."\n";
}
for ($m=0;$m<count($monkeys);$m++) {
	echo "monkey $m:  (".$monkeys[$m]['inspections'].")\n";
}


?>