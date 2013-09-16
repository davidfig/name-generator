<?php
/* https://github.com/davidfig/name-generator */

class NameList {
	public $names;
	
	private $lists;	
	private $handle;
	
	function __construct($list) {
		$this->lists = $list;
		$this->load();
	}
	
	private function load() {
		foreach ($this->lists as $name) {
			if (($this->handle = fopen("lists/".$name, "r")) !== FALSE) {
				switch ($name) {
					case "Old Testament (Hadley)": $this->loadLine(); break;
					case "US Baby Names (Hadley)": $this->loadUSBaby(); break;
				}			
				fclose($this->handle);
			}
		}
	}
	
	private function loadLine() {	
		while ($line = fgets($this->handle)) {
			if ($line != '') {
				$this->names[] = $line;
			}
		}
	}
	
	private function loadUSBaby() {
		while ($line = fgets($this->handle)) {
			$name = explode(',',$line)[1];
			$this->names[] = str_replace('"', '',$name);
		}
	}
	
	public function generate($random, $seed, $number) {
		if ($seed) {
			srand($seed);
		}
		
		for ($i = 0; $i < $number; $i++) {
			// ensure no duplicate entries
			do {
				$try = rand(0, count($this->names) - 1);
			} while (count($results) != 0 && in_array($try, $results));
			$results[] = $try;		
		}
		foreach ($results as $result) {
			echo $this->names[$result].'<br>';
		}
	}
}

// for debug purposes
if ($_GET['lists'] == '') {
	$_GET['lists'] = "Old Testament (Hadley)";
	$_GET['seed'] = '';
	$_GET['number'] = 5;
}

$lists = new NameList(explode(',', $_GET['lists']));
$lists->generate($_GET['randomize']=="true", $_GET['seed'], $_GET['number']);
?>