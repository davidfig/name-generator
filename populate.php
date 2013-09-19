<?php
/* https://github.com/davidfig/name-generator */

namespace YYFNG;

require "database.php";

//define (TEST, "Dwarf - LOTR (Wikipedia)");

$p = new Populate();
if (defined(TEST)) {
	echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head>';
	echo "Testing ".TEST."<br>";
	$p->testSource(TEST);
} else {
	$p->checkTables();
	$p->injestSources();
	echo '<br>Yopey Yopey\'s Fictional Name Generator is ready.';
}

class Populate {
	public function testSource($source) {
		$send["Filename"] = $source;
		$this->injestSource($send);
	}

	public function checkTables() {
		echo "Checking if tables exist . . .";
		try {
			Database::Query("SELECT * FROM Sources");
		}
		catch (\PDOException $e) {
			echo "NO<br>";	
			return $this->createTables();
		}
		echo "YES<br>";
	}
	
	public function cleanTables() {	
		echo "Cleaning out old tables. . . <br>";
		try {
			Database::Query("DROP TABLE Sources");
		}
		catch (\PDOException $e) {}
		
		try {
			Database::Query("DROP TABLE Names");
		}
		catch (\PDOException $e) {}
	}
	
	public function createTables() {
		echo "Creating new tables . . .<br>";
		Database::Query("
			CREATE TABLE Sources (
				SourcesKey smallint PRIMARY KEY AUTO_INCREMENT,
				FileName varchar(50),
				Title varchar(50),
				Count int,
				SourceName varchar(100),
				SourceURL varchar(100),
				Surname tinyint(1)
			)
		");
		
		Database::Query("
			CREATE TABLE Names (
				NameKey int PRIMARY KEY AUTO_INCREMENT,
				SourcesKey smallint,
				Name varchar(50)
			)
		");	
	}
	
	public function injestSources() {
		$sources = [];
		$n = 0;
		if (($handle = fopen("lists/list.config", "r")) !== FALSE) {
			$headers = fgetcsv($handle);
			while (($source = fgetcsv($handle)) !== FALSE) {
				for ($i=0; $i<count($source); $i++) {
					$sources[$n][$headers[$i]] = $source[$i];
				}
				$n++;
			}
			fclose($handle);
		}		
		
		foreach ($sources as $source) {
			echo "Checking if ".$source["Filename"]." is already injested . . .";
			$check = Database::FetchAll("SELECT * FROM Sources WHERE Filename=?", array($source["Filename"]));
			echo count($check)?"YES<br>":"NO<br>";
			
			if (!count($check)) {
				echo "Creating new source: ".$source["Filename"]."<br>";
				Database::Query("INSERT INTO Sources (Filename,Title,`Count`,SourceName,SourceURL,Surname) VALUES (?,?,?,?,?,?)",
					array($source["Filename"],$source["Title"],$source["Count"],$source["SourceName"],$source['SourceURL'],$source['IsSurname']));
				$source["key"] = Database::LastInsertId();
				$this->injestSource($source);
			}
		}
	}
	
	public function injestSource($source) {
		if (($this->handle = fopen("lists/".$source['Filename'], "r")) !== FALSE) {
			switch ($source['Filename']) {
				case "First Names (QuietAffiliate)":
				case "Last Names (QuietAffiliate)":
					$this->loadWords($source["key"]); break;
				case "US Baby Names (Hadley)": 
					$this->loadUSBaby($source["key"]); break;
				case "Last Names (US Census 2000)":					
					$this->loadLineFixCase($source["key"]); break;
				case "Old Testament (Hadley)": 
				case "Elf - Lord of the Rings (Wikipedia)":
				case "Dwarf (Bugmansbrewery)":					
					$this->loadLineEncode($source["key"]); break;
				default:
					$this->loadLine($source["key"]); break;

				
			}			
			fclose($this->handle);
		}
	}

	private function insertWord($word,$key) {
		if (!$key) {
			echo $word."<br>";
		} else {
			Database::Query("INSERT INTO Names (SourcesKey,Name) VALUES (?,?)",
				array($key,$word));
		}
	}
	
	private function loadWords($key) {
		$line = fgets($this->handle);
		$words = explode("\r",$line);
		foreach ($words as $word) {
			$this->insertWord($word,$key);
		}
	}

	private function loadLine($key) {	
		while ($line = fgets($this->handle)) {
			if ($line != '') {
				$this->insertWord($line,$key);
			}
		}
	}
	
	private function loadLineEncode($key) {	
		while ($line = fgets($this->handle)) {
			if ($line != '') {
				$this->insertWord(utf8_encode($line),$key);
			}
		}
	}

	private function loadLineFixCase($key) {	
		while ($line = fgets($this->handle)) {
			if ($line != '') {
				$this->insertWord(ucwords(strtolower(utf8_encode($line))),$key);
			}
		}
	}
	
	private function loadUSBaby($key) {
		while ($line = fgets($this->handle)) {
			$name = explode(',',$line)[1];
			$this->insertWord(str_replace('"', '',$name),$key);
		}
	}	
}
?>