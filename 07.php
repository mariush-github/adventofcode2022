<?php

define('CRLF',chr(0x0D).chr(0x0A));
define('LF',chr(0x0A));

$lines = explode(LF,trim(file_get_contents(__DIR__.'/inputs/input07.txt'),LF));

$folders = [];
$folders['/'] = new clsFolder('/','',0);
$maxLevel = 0;
$folder = &$folders['/'];

for ($i=0;$i<count($lines);$i++) {
	$line = explode(' ',$lines[$i]);
	if ($line[0] == '$') {
		if ($line[1]=='cd') {
			if ($line[2]=='/') $folder = &$folders['/'];
			if (($line[2]=='..') && ($folder->name!='/')) { $folder = &$folders[$folder->parent]; }
			if (($line[2]!='/') && ($line[2]!='..')) {
				$folder = &$folders[$folder->name.'/'.$line[2]];
			}
		}
	} else {
		if ($line[0]=='dir') {
			$folders[$folder->name.'/'.$line[1]] = new clsFolder($folder->name.'/'.$line[1],$folder->name,$folder->level+1);
			if ($maxLevel<$folder->level+1) $maxLevel = $folder->level+1;
		} else {
			$folder->addFile($line[1],intval($line[0]));
		}
	}
}
for ($level = $maxLevel;$level>0;$level--) {
	foreach ($folders as $key => $f) {
		$folder = &$folders[$key];
		if ($folder->level==$level){
			$folder_parent = &$folders[$folder->parent];
			$folder->sizeAll += $folder->size;
			$folder_parent->sizeAll += $folder->sizeAll;
		}
	}
}
$folder = &$folders['/'];
$folder->sizeAll+=$folder->size;
$score = 0;
foreach ($folders as $key => $f) {
	$folder = &$folders[$key];
	if ($folder->sizeAll<100000) $score +=$folder->sizeAll;
}

echo "score 01 = $score\n";

$folder = &$folders['/'];
$space_left = 70000000 - $folder->sizeAll;
$space_need = 30000000 - $space_left;
echo "space used : ".$folder->sizeAll."\n";
echo "space left : ".$space_left."\n";
echo "space need : ".$space_need."\n";

$sizes = [];
foreach ($folders as $key=>$value) {
	array_push($sizes,$folders[$key]->sizeAll);
}
sort($sizes);
while ($sizes[0]<$space_need) {
	array_shift($sizes);
}
echo "score 02 = ".$sizes[0]."\n";



class clsFolder {
	public $name;
	public $files;
	public $size;
	public $sizeAll;
	public $parent;
	public $level;
	function __construct($name = "",$parent='',$level=0) {
		$this->name = $name;
		$this->parent = $parent;
		$this->files = [];
		$this->size = 0;
		$this->sizeAll = 0;
		$this->level = $level;
	}
	function addFile($filename,$size) {
		if (isset($this->files[$filename])!==FALSE) return false;
		$this->files[$filename] = $size;
		$this->size +=$size;
		return true;
	}
}

?>