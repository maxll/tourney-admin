<?php

?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="js/group.js"></script>
	</head>
	
	
	<body>
		<h3>Übersicht über Gruppen:</h3>
		<section style="margin-left:20px;">

			<table cellpadding="5" cellspacing="0" border="1">
				<tr>
					<th>Name</th>
					<th>Spieklasse</th>
					<th>System</th>
					<th>Einstiegsgruppe</th>
					<th>Zeit pro Spiel / min</th>
					<th>Mannschaften</th>
				</tr>
			</table>
			<br /><br />
		</section>
		<h3>Neue Gruppe anlegen:</h3>
		<section data-bind="visible: teams().length > 0" style="padding: 10px;">
			<table cellspacing="0" cellpadding="5px" border="0">
				<thead>
				<tr>
					<th />
					<th style="text-align:left">Name</th>
					<th style="text-align:left">Spielklasse</th>
					<th style="text-align:left">Einstiegsgruppe</th>
					<th style="text-align:left">Mannschaften in Gruppe</th>
					<th style="text-align:left">Spielsystem</th>
					<th style="text-align:left">Abweichende Spielzeit</th>
				</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</section>



		<section>
		<!--  	Wenn Auswahl der vorherigen Gruppe = "keine", Auswahl der Gruppengröße zulassen.
			 	Sonst Gruppengröße berechnen durch Anzahl der Gruppen und Platzierungsinterval -->		
				
				Name der Gruppe:&nbsp;
				<input type="text" name="group_name" size="20" required="required" placeholder="Name" />
				<br /><br />
				
				Spielklasse:&nbsp;
				<select name="div_nr" size="1">
					<?php 
					$sql = "SELECT * FROM division";
					$result_div = $dbConnector->performSelectQuery($sql);
					
					if($num = mysqli_num_rows($result_div) == 0 ){
						echo "<option value=\"0\">bitte eine Spielklasse erstellen</option>";
					} else {
						while($row_div = mysqli_fetch_assoc($result_div)){
							echo "<option value=\"{$row_div['nr']}\">{$row_div['name']}</option>";
						}
					}
					?>
				</select>
				<br /><br />
				Gruppe ist Einstiegsgruppe:&nbsp;
				<input type="radio" name="entrance_group" value="ja" onclick="show_team_size_selector(), calculate_team_size()" checked />ja&nbsp;
				<input type="radio" name="entrance_group" value="nein" />nein
				
				<p id="team_size_from_group">
				Auswahl der Mannschaften aus vorherigen Gruppen:<br />
				Plätze <input class="input_number" type="text" name="position_start" size="1" value="1" onclick="calculate_team_size()" onkeyup="calculate_team_size()" />
				bis <input class="input_number" type="text" name="position_end" size="1" value="2" onclick="calculate_team_size()" onkeyup="calculate_team_size()" />
				aus den Gruppen:&nbsp;
				</p>
				
				<p id="team_size_selector">
				Gruppengröße:&nbsp;
				<select name="team_count" size="1" onclick="calculate_team_size()">
					<?php 
						echo "<option value=\"1\">1 Mannschaft</option>";
						for($i=2;$i<9;$i++){
							echo "<option value=\"$i\">$i Mannschaften</option>";
						}	
					?>
				</select>
				</p>
				
				Spielsystem (abhängig von Gruppengröße):&nbsp;
				<select name="sys_nr" size="1">
					<?php 
					$sql = "SELECT * FROM system";
					$result = $dbConnector->performSelectQuery($sql);
					
					if($num = mysqli_num_rows($result) == 0 ){
						echo "<option value=\"0\">bitte Spielsysteme einfügen</option>";
					} else {
						while($row = mysqli_fetch_assoc($result)){
							echo "<option value=\"{$row['nr']}\">{$row['name']}</option>";
						}
					}
					?>
				</select>&nbsp;
				<a href="index.php?content=system&function=showsystem&system=1300" target="_blank">...anzeigen</a>
				<br /><br />Für diese Gruppe abweichende Spielzeit festlegen:
				<br /><br />
				Mannschaften:<br />
				<p id="team_choice">
				bla
				</p>
				<button>Gruppe anlegen</button>
				<p><br /><br /><br /></p>
		</section>
	</body>
</html>
