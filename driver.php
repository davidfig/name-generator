<!-- https://github.com/davidfig/name-generator -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Yopey Yopey's Fictional Name Generator</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="base.css" />	
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="script.js"></script>
</head>
<body>	
	<h1>Yopey Yopey's Fictional Name Generator</h1>
	<div id="settings">
		<h3>Randomize Syllables of Names</h3>
		<!-- From: http://proto.io/freebies/onoff/ -->
		<div class="onoffswitch">
			<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="randomize">
			<label class="onoffswitch-label" for="randomize">
				<div class="onoffswitch-inner"></div>
				<div class="onoffswitch-switch"></div>
			</label>
		</div>
		<h3>First-Name Sources</h3>
		<table class="sources">
			<tr>
				<th id="t1col0">Use</th>
				<th id="t1col1">Name List</th>
				<th id="t1col2">Name Count</th>
				<th id="t1col3">Source</th>
			</tr>
			<?php			
				require "database.php";
				$sources = YYFNG\Database::FetchAll("SELECT * FROM Sources");
				$first = true;
				foreach ($sources as $source) {				
					if (!$source['Surname']) {
						echo '<tr class="'.($first?'default ':'').
							'givenName" name="'.$source['SourcesKey'].'">
							<td>'.($first?'&#x2713':'').'</td>
							<td>'.$source['Title'].'</td>
							<td>'.number_format($source['Count']).'</td>
							<td><a href="'.$source['SourceURL'].'">'.$source['SourceName'].'</a>
							</tr>';
						$first = false;					
					}
				}
			
			?>
		</table>
		<h3>Last-Name Sources</h3>
		<table class="sources">
			<tr>
				<th id="t2col0">Use</th>		
				<th id="t2col1">Name List</th>
				<th id="t2col2">Name Count</th>
				<th id="t2col3">Source</th>
			</tr>
			<?php 
				$first = true;
				foreach ($sources as $source) {				
					if ($source['Surname']) {
						echo '<tr class="'.($first?'default ':'').
						'familyName" name="'.$source['SourcesKey'].'">
							<td>'.($first?'&#x2713':'').'</td>
							<td>'.$source['Title'].'</td>
							<td>'.number_format($source['Count']).'</td>
							<td><a href="'.$source['SourceURL'].'">'.$source['SourceName'].'</a>
							</tr>';
						$first = false;							
					}					
				}			
			?>
		</table>
	</div>
	<div id="names">
		<div id="results">Generating . . .</div>
		<button id="generate">More</button>		
	</div>
	<div id="footer">
		<div>Website source code: <a href="https://github.com/davidfig/name-generator">https://github.com/davidfig/name-generator</a> (BSD license)</div>		
	</div>
</body>
</html>