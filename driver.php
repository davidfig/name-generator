<!-- https://github.com/davidfig/name-generator -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Yopey Yopey's Fictional Name Generator</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script>
		var highlightColor = "rgb(200, 200, 200)";
		function nameLists(type) {
			var lists = "";
			$("." + type).each(function(index) {
				if ($(this).css("background-color") == highlightColor) {
					lists += $(this).attr("name") + ",";
				}				
			});
			if (lists.length) {
				lists = lists.substring(0, lists.length - 1);				
			}
			return lists;
		}
		
		function generateName() {
			if ($("#generate").button("option", "disabled")) {
				return;
			}
			
			var lists = nameLists("givenName");
			if (lists.length) {
				lists = "given=" + lists;
			}
			var family = nameLists("familyName");
			if (family) {
				if (lists.length) {
					lists = lists + "&";
				}
				lists = lists + "family=" + family;
			}
			if (lists.length == 0) {
				$("#results").html("Please include at least one Source.");
				$("#results").effect("highlight", {}, 3000);
			} else {
				generating = true;
				
				$("#results").html("Generating . . .");				
				$("#generate").button("disable");
				$.get("generate.php?" + lists + "&number=" + $("#number").val()
					+ "&randomize=" + ($("#randomize").is(":checked")?"true":"false"),
					function (data) {
						$("#results").html(data);
						$("#generate").button("enable");
				});
			}
		}
		
		function selectRow(e) {
			if ($(this).css("background-color") == highlightColor) {
				$(this).css("background-color", "white");
			} else {
				$(this).css("background-color", highlightColor);
			}

		}
		
		$(document).ready(function() {		
			$(window).bind("keypress", function(e){
				if (e.keyCode == 13) {
					generateName();
				}
			});
			$(document).tooltip();
			
			$("#generate").button().click(generateName);
			$(".givenName").click(selectRow);	
			$(".familyName").click(selectRow);
			$(".givenName").css("cursor","pointer");
			$(".familyName").css("cursor","pointer");
			$(".default").css("background-color", highlightColor);
			$("#t2col1").width($("#t1col1").width());			
			$("#t2col2").width($("#t1col2").width());			
			$("#t2col3").width($("#t1col3").width());						
		});
	</script>
</head>
<body style="font-size:150%">	
	<h1>Yopey Yopey's Fictional Name Generator</h1>
	<div style="width:50%;float:right">
		<button id="generate">Generate Names</button>
		<label>Randomize syllables <input type="checkbox" id="randomize"></label><br>
		
		<h2><input type="text" id="number" size="1" value="10">Results</h2>
		<div id="results">Empty.</div>
	</div>
	<div style="width:50%">
		<h2>Given Name Sources</h2>
		<table style="text-align:center">
			<tr>
				<th id="t1col1"></th>
				<th id="t1col2">Name Count</th>
				<th id="t1col3">Source</th>
			</tr>
			<?php			
				require "database.php";
				$sources = YYFNG\Database::FetchAll("SELECT * FROM Sources");
				$first = true;
				foreach ($sources as $source) {				
					if (!$source['Surname']) {
						echo '<tr class="';
						if ($first) {
							echo 'default ';
							$first = false;
						}
						echo 'givenName" name="'.$source['SourcesKey'].'">
							<td>'.$source['Title'].'</td>
							<td>'.$source['Count'].'</td>
							<td><a href="'.$source['SourceURL'].'">'.$source['SourceName'].'</a>
							</tr>';
					}
				}
			
			?>
		</table>
		<h2>Family Name Sources</h2>
		<table style="text-align:center">
			<tr>
				<th id="t2col1"></th>
				<th id="t2col2">Name Count</th>
				<th id="t2col3">Source</th>
			</tr>
			<?php 
				$first = true;
				foreach ($sources as $source) {				
					if ($source['Surname']) {
						echo '<tr class="';
						if ($first) {
							echo 'default ';
							$first = false;
						}
						echo 'familyName" name="'.$source['SourcesKey'].'">
							<td>'.$source['Title'].'</td>
							<td>'.$source['Count'].'</td>
							<td><a href="'.$source['SourceURL'].'">'.$source['SourceName'].'</a>
							</tr>';
					}
				}			
			?>
		</table>
	</div>
	<div style="font-size:75%;position:fixed;bottom: 0;padding-top:10px;padding-bottom:10px;border-top:1px dashed black;font-style:italic">
		<div>Website source code: <a href="https://github.com/davidfig/name-generator">https://github.com/davidfig/name-generator</a> (BSD License)</div>		
	</div>
</body>
</html>