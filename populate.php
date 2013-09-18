<?php
/* https://github.com/davidfig/name-generator */

namespace YYFNG;

require "database.php";

/* CHANGE FALSE TO TRUE FOR FIRST RUN TIME */
if (false) {
	$p = new Populate();
	$p->createTables();
	$p->injestSources();
}

class Populate {
	public function createTables() {
		echo "Cleaning out old tables. . . <br>";
		try {
			Database::Query("DROP TABLE Sources");
		}
		catch (\PDOException $e) {}
		
		try {
			Database::Query("DROP TABLE Names");
		}
		catch (\PDOException $e) {}
		
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
			echo "Creating new source: ".$source["Filename"]."<br>";
			Database::Query("INSERT INTO Sources (Filename,Title,`Count`,SourceName,SourceURL,Surname) VALUES (?,?,?,?,?,?)",
				array($source["Filename"],$source["Title"],$source["Count"],$source["SourceName"],$source['SourceURL'],$source['IsSurname']));
			$source["key"] = Database::LastInsertId();
			$this->injestSource($source);
		}
	}
	
	public function injestSource($source) {
		if (($this->handle = fopen("lists/".$source['Filename'], "r")) !== FALSE) {
			switch ($source['Filename']) {
				case "Old Testament (Hadley)": 
				case "Elf - Lord of the Rings (Wikipedia)":
					$this->loadLine($source["key"]); break;					
				case "First Names (QuietAffiliate)":
				case "Last Names (QuietAffiliate)":
					$this->loadWords($source["key"]); break;
				case "US Baby Names (Hadley)": 
					$this->loadUSBaby($source["key"]); break;
				case "Last Names (US Census 2000)":					
					$this->loadLineFixCase($source["key"]); break;
				default: echo "Error: Configuration for ".$name." not found."; return;
			}			
			fclose($this->handle);
		}
	}

	private function insertWord($word,$key) {
		Database::Query("INSERT INTO Names (SourcesKey,Name) VALUES (?,?)",
			array($key,$word));
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