<!-- https://github.com/davidfig/name-generator -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Yopey Yopey's Fictional Name Generator</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>	
	<script>
		function clipboard() {
			window.prompt ("Copy to clipboard: CTRL+C, ENTER", $("#results").html());
		}
		
		function generateName() {
			var lists = "";
			$(".list").each(function(index) {
				if ($(this).is(":checked")) {
					lists += $(this).attr("name") + ",";
				}
			});
			if (lists.length == 0) {
				$("#results").html("Please include at least one Source.");
				$("#results").effect("highlight", {}, 3000);
			} else {
				lists = lists.substring(0, lists.length - 1);		
				$("#results").html("Loading . . .");
				$.get("generate.php?lists=" + lists + "&number=" + $("#number").val()
					+ "&randomize=" + ($("#randomize").is(":checked")?"true":"false")
					+ "&seed=" + $("#seed").val(), function (data) {
						$("#results").html(data);
						$("#results").effect("highlight", {}, 3000);
				});
			}
		}
		
		$(window).bind("keypress", function(e){
			if (e.keyCode == 13) {
				generateName();
			}
		});
	</script>
</head>
<body>	
	<h1>Yopey Yopey's Fictional Name Generator</h1>
	<div style="width:50%;float:right">
		<h2>Results</h2>
		<div id="results">Empty.</div>
		<br><input type="button" value="Copy to Clipboard" onclick="clipboard()">
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
					echo '<input class="list" type="checkbox" name="'.$file.'"><a href="lists/'.$file.'" title="'.$name.'">'.$name.'</a><br>';
				}			
			}
		?>
		<h2>Settings</h2>
		<input type="text" id="seed" size="1" value="">Seed (leave blank to use random seed)<br>
		<input type="text" id="number" size="1" value="20">Number to generate<br>
		<input type="checkbox" name="randomize">Randomize names<br>
		<br><input type="submit" value="Generate Names" onclick="generateName()"> (or press ENTER)
	</div>
	<div style="margin-top:100px;padding-top:10px;border-top:1px dashed black;font-style:italic">
		<div>US Baby List and Old Testament List: <a href="https://github.com/hadley/data-baby-names">https://github.com/hadley/data-baby-names</a></div>
		<div>Basics of randomizing the name lists: <a href="http://www.skorks.com/2009/07/how-to-write-a-name-generator-in-ruby/">How to Write a Name Generator (In Ruby)</a><div>	
		<div style="line-height: 300%">Source code is available at: <a href="https://github.com/davidfig/name-generator">https://github.com/davidfig/name-generator</a> (BSD License)</div>		
	</div>
</body>
</html>