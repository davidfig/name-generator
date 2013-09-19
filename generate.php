<?php
/* https://github.com/davidfig/name-generator */

namespace YYFNG;

require "database.php";

class NameList {
	public $chosen;
	
	private $list;	
	private $qFilename;
	
	function __construct($list) {
		for ($i=0; $i<count($list); $i++) {
			$this->qFilename .= 'SourcesKey=? OR ';
		}
		$this->qFilename = substr($this->qFilename,0,strlen($this->qFilename)-strlen(' OR '));
		$this->list = $list;
	}
	
	public function generate($number) {
		$names = $this->getWords($number*5);
		$j = 0;
		for ($i = 0; $i < $number; $i++) {
			// ensure no duplicate entries
			do {
				$try = $names[$j++]['Name'];
				if ($j > count($names)) {
					break;
				}
			} while (count($this->chosen) != 0 && in_array($try, $this->chosen));
			$this->chosen[] = $try;		
		}
	}

	private function getWords($number) {
		return Database::FetchAll("SELECT Name FROM Names WHERE ".$this->qFilename." ORDER BY RAND() LIMIT ".$number, $this->list);
	}
	
	private function returnSyllables($word) {
        $vowels = [ 'a', 'e', 'i', 'o', 'u', 'y', 'ó', 'ë', 'á', 'ö', 'í', 'ú', 'Ã', 'ë' ];
		$syllables = [];
		$lastSyllable = 0;
		$leftOver = "";
        for ($i=0; $i<mb_strlen($word); $i++) {
			if (in_array($word[$i], $vowels)) {
				if ($i + 1 < mb_strlen($word)) {
					$i++;
					if (in_array($word[$i], $vowels)) {
						$i++;
					}
				}
				$syllables[] = mb_substr($word, $lastSyllable, $i - $lastSyllable + 1);
				$lastSyllable = $i + 1;
				$leftOver = mb_substr($word, $i + 1);
			}
        }
		if ($leftOver) {
			$syllables[count(syllables)-1] .= $leftOver;
		}

        return $syllables;
    }
	

	public function generateRandom($number) {
		$names = $this->getWords($number*10);
		$k = 0;
		for ($i = 0; $i < $number; $i++) {
			$select = $names[$k++]['Name'];
			$syllables = $this->returnSyllables($select);
			$count = count($syllables);
			$result = trim($syllables[0]);
			for ($j = 1; $j < $count; $j++) {
				do {
					$select = $names[$k++]['Name'];
					if ($k > count($names)) {
						break;
					}
					$syllables = $this->returnSyllables($select);
				} while (count($syllables) < $j);
				$result .= trim($syllables[$j]);
			}
			$this->chosen[] = $result;
		}			
	}
}

$number = 10;

if ($_GET['given']) {
	$given = new NameList(explode(',', $_GET['given']));
	if ($_GET['randomize']=="true") {
		$given->generateRandom($number);	
	} else {
		$given->generate($number);	
	}
}
if ($_GET['family']) {
	$family = new NameList(explode(',', $_GET['family']));
	if ($_GET['randomize']=="true") {
		$family->generateRandom($number);
	} else {
		$family->generate($number);	
	}	
}

if (count($given->chosen) || count($family->chosen)) {
	for ($i=0; $i<$number; $i++) {
		echo '<div class="result">';
		if (count($given->chosen) && count($family->chosen)) {
			echo $given->chosen[$i].' '.$family->chosen[$i];
		} else if (count($given->chosen)) {
			echo $given->chosen[$i];
		} else {
			echo $family->chosen[$i];
		}
		echo '</div>';
	}
}

?>