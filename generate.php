<?php
/* https://github.com/davidfig/name-generator */

class NameList {
	public $chosen;
	
	private $names;
	
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
					case "Old Testament (Hadley)": 
					case "Elf - Lord of the Rings (Wikipedia)":
						$this->loadLine(); break;					
					case "First Names (QuietAffiliate)":
					case "Last Names (QuietAffiliate)":
						$this->loadWords(); break;
					case "US Baby Names (Hadley)": $this->loadUSBaby(); break;
					default: echo "Error: List not found."; return;
				}			
				fclose($this->handle);
			}
		}
	}

	private function loadWords() {
		$line = fgets($this->handle);
		$words = explode("\r",$line);
		if (count($this->names)) {
			$this->names = array_merge($this->names, $words);
		}
		else {
			$this->names = $words;		
		}
	}
	
	private function loadLine() {	
		while ($line = fgets($this->handle)) {
			if ($line != '') {
				$this->names[] = utf8_encode($line);
			}
		}
	}
	
	private function loadUSBaby() {
		while ($line = fgets($this->handle)) {
			$name = explode(',',$line)[1];
			$this->names[] = str_replace('"', '',$name);
		}
	}
	
	public function generate($random) {
		if (count($this->names)) {
			if (!$number || !is_numeric($number)) {
				$number = 10;
			}
			
			if ($number > count($this->names)) {
				echo "Error: not enough names in the selected name list."; 
				return;
			}
			
			for ($i = 0; $i < $number; $i++) {
				// ensure no duplicate entries
				do {
					$try = $this->names[rand(0, count($this->names) - 1)];
				} while (count($this->chosen) != 0 && in_array($try, $this->chosen));
				$this->chosen[] = $try;		
			}
		}
	}
	
	private function returnSyllables($word) {
        $vowels = [ 'a', 'e', 'i', 'o', 'u', 'y' ];
		$syllables = [];
		$lastSyllable = 0;
		$leftOver = "";
        for ($i=0; $i<strlen($word); $i++) {
			if (in_array($word[$i], $vowels)) {
				if ($i + 1 < strlen($word)) {
					$i++;
					if (in_array($word[$i], $vowels)) {
						$i++;
					}
				}
				$syllables[] = substr($word, $lastSyllable, $i - $lastSyllable + 1);
				$lastSyllable = $i + 1;
				$leftOver = substr($word, $i + 1);
			}
        }
		if ($leftOver) {
			$syllables[count(syllables)-1] .= $leftOver;
		}

        return $syllables;
    }
	

	public function generateRandom($number) {
		if (count($this->names)) {
			if (!$number || !is_numeric($number)) {
				$number = 10;
			}
			
			if ($number > count($this->names)) {
				echo "Error: not enough names in the selected name list."; 
				return;
			}

			for ($i = 0; $i < $number; $i++) {
				$select = $this->names[rand(0, count($this->names) - 1)];
				$syllables = $this->returnSyllables($select);
				$count = count($syllables);
				$result = $syllables[0];
				for ($j = 1; $j < $count; $j++) {
					do {
						$select = $this->names[rand(0, count($this->names) - 1)];
						$syllables = $this->returnSyllables($select);
					} while (count($syllables) < $j);
					$result .= $syllables[$j];
				}
				$this->chosen[] = $result;
			}			
		}
	}
}

if ($_GET['given']) {
	$given = new NameList(explode(',', $_GET['given']));
	if ($_GET['randomize']=="true") {
		$given->generateRandom($_GET['number']);	
	} else {
		$given->generate($_GET['number']);	
	}
}
if ($_GET['family']) {
	$family = new NameList(explode(',', $_GET['family']));
	if ($_GET['randomize']=="true") {
		$family->generateRandom($_GET['number']);
	} else {
		$family->generate($_GET['number']);	
	}	
}

if (count($given->chosen) || count($family->chosen)) {
	for ($i=0; $i<$_GET['number']; $i++) {
		if (count($given->chosen) && count($family->chosen)) {
			echo $given->chosen[$i].' '.$family->chosen[$i].'<br>';
		} else if (count($given->chosen)) {
			echo $given->chosen[$i].'<br>';
		} else {
			echo $family->chosen[$i].'<br>';
		}
	}
}

?>