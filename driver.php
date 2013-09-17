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
			if ($("#generate").attr("disabled") == "disabled") {
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
				$("#generate").attr("disabled","disabled");
				$.get("generate.php?" + lists + "&number=" + $("#number").val()
					+ "&randomize=" + ($("#randomize").is(":checked")?"true":"false"),
					function (data) {
						$("#results").html(data);
						$("#results").effect("highlight", {}, 3000);
						$("#generate").removeAttr("disabled");
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

			$(".quietaffiliate").click(function() { window.open("http://www.quietaffiliate.com/free-first-name-and-last-name-databases-csv-and-sql"); return false;});
			$(".hadley").click(function() { window.open("https://github.com/hadley/data-baby-names"); return false;});
			$(".wikipedia").click(function() { window.open("http://en.wikipedia.org/wiki/List_of_Middle-earth_Elves"); return false;});
			
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
		<!--<input id="generate" type="submit" value="Generate Names" onclick="generateName()"> (or press ENTER) -->
		<button id="generate">Generate Names</button>
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
			</tr><tr class="default givenName" name="First Names (QuietAffiliate)">
				<td>First Names</td>
				<td>5,494</td>
				<td><a href="#" class="quietaffiliate">quietaffiliates.com</a></td>
			</tr><tr class="givenName" name="US Baby Names (Hadley)">
				<td>U.S. Baby Names</td>
				<td>258,000</td>
				<td><a href="#" class="hadley">github.com/hadley</a></td>
			</tr><tr class="givenName" name="Old Testament (Hadley)">
				<td>Old Testament Names</td>
				<td>147</td>
				<td><a href="#" class="hadley">github.com/hadley</a></td>
			</tr><tr class="givenName" name="Elf - Lord of the Rings (Wikipedia)">
				<td>Elf Names - LOTR</td>
				<td>93</td>
				<td><a href="#" class="wikipedia">wikipedia.org</a></td>
			</tr>
		</table>
		<h2>Family Name Sources</h2>
		<table style="text-align:center">
			<tr>
				<th id="t2col1"></th>
				<th id="t2col2">Name Count</th>
				<th id="t2col3">Source</th>
			</tr><tr class="default familyName" name="Last Names (QuietAffiliate)">
				<td>Last Names</td>
				<td>88,799</td>
				<td><a href="#" class="quietaffiliate">quietaffiliates.com</a></td>
			</tr>
		</table>
		<h2>Settings</h2>		
		<label><input type="checkbox" name="randomize">Randomize syllables in names</label><br>
	</div>
	<div style="font-size:75%;position:fixed;bottom: 0;padding-top:10px;border-top:1px dashed black;font-style:italic">
		<div>Basics of shuffling the name lists: <a href="http://www.skorks.com/2009/07/how-to-write-a-name-generator-in-ruby/">How to Write a Name Generator (In Ruby)</a><div>	
		<div style="line-height: 300%">Website source code: <a href="https://github.com/davidfig/name-generator">https://github.com/davidfig/name-generator</a> (BSD License)</div>		
	</div>
</body>
</html>