<?php
/* https://github.com/davidfig/name-generator */

namespace YYFNG;

require "database.php";

class NameList {
	public $chosen;
	
	private $names;
	
	private $lists;	
	private $handle;
	
	function __construct($list) {	
		for ($i=0; $i<count($list); $i++) {
			$qFilename .= 'FileName=? OR ';
		}
		$qFilename = substr($qFilename,0,strlen($qFilename)-strlen(' OR '));
		print_r($list);
		$sourcesKey = Database::FetchAll("SELECT SourcesKey FROM Sources WHERE ".$qFilename,$list);
		print_r($sourcesKey);
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
				$result = trim($syllables[0]);
				for ($j = 1; $j < $count; $j++) {
					do {
						$select = $this->names[rand(0, count($this->names) - 1)];
						$syllables = $this->returnSyllables($select);
					} while (count($syllables) < $j);
					$result .= trim($syllables[$j]);
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