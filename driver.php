<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Yopey Yopey's Name Generator</title>
</head>
<body>	
	<h1>Yopey Yopey's Name Generator</h1>
	<div style="width:50%;float:right">
		<h2>Results</h2>
		<div id="results">Empty.</div>
	</div>
	<div style="width:50%">
		<h2>Sources</h2>
		<?php
			$files = scandir("lists");
			foreach ($files as $file) {
				if ($file != '.' && $file != '..') {
					$explode = explode(".", $file);
					$name = $explode[0];
					$type = $explode[1];			
					echo '<input type="checkbox" name="'.$file.'"><a href="lists/'.$file.'" title="'.$name.'">'.$name.'</a><br>';
				}			
			}
		?>
		<h2>Settings</h2>
		<input type="text" name="number" size="1" value="20">Number<br>
		<input type="checkbox" name="random">Randomize Name<br>
	</div>
	<div style="margin-top:100px;padding-top:10px;border-top:1px dashed black;font-style:italic">
		<div>US Baby List and Old Testament List: <a href="https://github.com/hadley/data-baby-names">https://github.com/hadley/data-baby-names</a></div>
		<div>Basics of randomizing the name lists: <a href="http://www.skorks.com/2009/07/how-to-write-a-name-generator-in-ruby/">How to Write a Name Generator (In Ruby)</a><div>	
	</div>
</body>
</html>