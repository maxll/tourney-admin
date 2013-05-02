<?php
/**
 *
 * @author Maximilian Walden
 * @copyright Copyright &copy; 28.03.2013, Maximilian Walden
 * @version 0.1
 */

// require_once '/class/MySQLConnector.class.php';

// $dbConnector = new MySQLConnector();
// $connection = $dbConnector->getConnection();


?>
<!DOCTYPE html>
<html>
	<head>
	<script type="text/javascript">
		function activate_modified_game_length(){
			document.getElementsByName("modified_game_length")[0].disabled = false;
		}
		function deactivate_modified_game_length(){
			document.getElementsByName("modified_game_length")[0].disabled = true;
		}
		function show_team_size_selector(){
			document.getElementById("team_size_from_group").style.display = 'none';
			document.getElementById("team_size_selector").style.display = 'block';
		}
		function show_team_size_from_group(){
			document.getElementById("team_size_from_group").style.display = 'block';
			document.getElementById("team_size_selector").style.display = 'none';
		}
		
		function calculate_team_size(){
			var team_size_selector = document.getElementsByName("team_count")[0];
			var position_start = document.getElementsByName("position_start")[0].value;
			var position_end = document.getElementsByName("position_end")[0].value;
			var team_size = 0;
			var selected_groups = 0;

			for(var i=0;i<document.getElementsByName("group_choice").length;i++){
				if(document.getElementsByName("group_choice")[i].checked){
					selected_groups++;
				}
			}
			
			if(document.getElementById("team_size_selector").style.display != 'none'){
				//hier selector auswerten
				team_size = team_size_selector.value;
				show_team_input(team_size);
			} else {
				// platzintervall * Anzahl gewählter Gruppen
				team_size = ((position_end - position_start) + 1) * selected_groups;
				show_team_input(team_size);
			}
		}

		/**
		* Alle verfügbaren Teams (Teams in Division ohne stats_per_group in Gruppe) als Checkboxen darstellen
		* Sobald die Anzahl der Teams pro Gruppe erreicht ist, alle anderen Checkboxen disablen
		* zusätzlich eine Random Checkbox --> alle anderen werden deaktiviert
		*/
		function show_team_input(nr){
			var data = "";
			data = "Mannschaftszwahl: " + nr + "<br />";

			for(var i=0;i<nr;i++){
				data += "<input type=\"checkbox\" name=\"\" value=\"\" />Team:" + (i+1) + "<br />";
			}
			document.getElementById("team_choice").innerHTML = data;
		}
	</script>
	</head>
	
	
	<body>
		<h3>Übersicht über Gruppen:</h3>
		<section style="margin-left:20px;">
			<?php 
				if(isset($_POST['group_name'])){
				
// 					$sql = "INSERT INTO group (name, div_nr)
// 							VALUES ('{$_POST['team_name']}', {$_POST['div_nr']})";
				
// 					if (mysqli_query($connection, $sql)){
// 					//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
// 					} else {
// 					echo "<li>Error in INSERT INTO <strong>division</strong>: " . mysqli_error($connection) . "</li>";
// 					}
				}
			
			
			
			
				$sql = "SELECT name,
							(SELECT name FROM division WHERE group.div_nr = division.nr) div_name,
 							(SELECT name FROM system WHERE group.sys_nr = system.nr) sys_name,
 							startgroup,
 							modified_game_length
 						FROM `group`";
					
				$result = $dbConnector->performSelectQuery($sql);
			?>
			<table cellpadding="5" cellspacing="0" border="1">
				<tr>
					<th>Name</th>
					<th>Spieklasse</th>
					<th>System</th>
					<th>Einstiegsgruppe</th>
					<th>Zeit pro Spiel / min</th>
					<th>Mannschaften</th>
				</tr>
				<?php
					while($row = mysqli_fetch_assoc($result)){ 
						echo "<tr>";
						echo "<td>{$row['name']}</td>";
						echo "<td>{$row['div_name']}</td>";
						echo "<td>{$row['sys_name']}</td>";
						echo "<td>";
							if($row['startgroup'] == 0){
								echo "ja";
							}
						echo "</td>";
						echo "<td>{$row['modified_game_length']}</td>";
						echo "<td>";
						//echo "<ul>";
							$sql = "SELECT team_name FROM stats_per_group WHERE stats_per_group.group_name = '{$row['name']}'";
							$result_teams = $dbConnector->performSelectQuery($sql);
							while($row_teams = mysqli_fetch_assoc($result_teams)){
								//echo "<li>{$row_teams['team_name']}</li>";
								echo $row_teams['team_name'] . "<br />";
							}
						//echo "</ul>";
						echo "</td>";
						echo "</tr>";
					}
				?>	
			</table>
			<br /><br />
		</section>
		<h3>Neue Gruppe anlegen:</h3>
		<section>
		<!--  	Wenn Auswahl der vorherigen Gruppe = "keine", Auswahl der Gruppengröße zulassen.
			 	Sonst Gruppengröße berechnen durch Anzahl der Gruppen und Platzierungsinterval -->		
			<?php 
				$sql = "SELECT * FROM `group`";
					
				$result = $dbConnector->performSelectQuery($sql);
					
				$rowIsEmpty = true;
				if($num = mysqli_num_rows($result) != 0 ){
					$rowIsEmpty = false;
				}
			?>
			<form action="index.php?content=group" method="post">
				
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
				<?php 
					if($rowIsEmpty){
						echo "<input type=\"radio\" name=\"entrance_group\" value=\"nein\" disabled/>nein&nbsp;&nbsp;&nbsp;";
					} else {
						echo "<input type=\"radio\" name=\"entrance_group\" value=\"nein\" onclick=\"show_team_size_from_group(),  calculate_team_size()\"/>nein&nbsp;&nbsp;&nbsp;";
					}
				?>
				
				<p id="team_size_from_group" class="hidden">
				Auswahl der Mannschaften aus vorherigen Gruppen:<br />
				Plätze <input class="input_number" type="text" name="position_start" size="1" value="1" onclick="calculate_team_size()" onkeyup="calculate_team_size()" />
				bis <input class="input_number" type="text" name="position_end" size="1" value="2" onclick="calculate_team_size()" onkeyup="calculate_team_size()" />
				aus den Gruppen:&nbsp;
				<?php
					if(!$rowIsEmpty){
						while($row = mysqli_fetch_assoc($result)){
							echo "<input type=\"checkbox\" name=\"group_choice\" value=\"{$row['name']}\" onclick=\"calculate_team_size()\" />{$row['name']}";
						}
					} else {
						echo "<small>&lt;keine Gruppen vorhanden&gt;</small>";
					}
					
				?>
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
				<!-- TODO Anpassung der system_nr an aktuell ausgewähltes System -->
				<a href="index.php?content=system&function=showsystem&system=1300" target="_blank">...anzeigen</a>
				<br /><br />
				
				<?php
					// TODO Anpassung der Division_NR an aktuell ausgewählt Division oben
					$result = $dbConnector->performSelectQuery("SELECT * FROM division WHERE division.nr = 1");
					$row = mysqli_fetch_assoc($result);
					echo "Für diese Gruppe abweichende Spielzeit festlegen:";
					echo "<input type=\"radio\" name=\"modified_game_length_radio\" value=\"ja\" onclick=\"activate_modified_game_length()\"/>&nbsp;ja&nbsp;";
					echo "<input type=\"radio\" name=\"modified_game_length_radio\" value=\"nein\" onclick=\"deactivate_modified_game_length()\" checked/>&nbsp;nein&nbsp;&nbsp;";
					echo "<input class=\"input_number\" type=\"text\" name=\"modified_game_length\" size=\"1\" value=\"{$row['game_length']}\" disabled/>";
				?>
				Minuten
				<br /><br />
				Mannschaften:<br />
				<p id="team_choice">
				bla
				</p>
				<input type="submit" value="Gruppe anlegen" />
				<p><br /><br /><br /></p>
			</form>
		</section>
	</body>
</html>
