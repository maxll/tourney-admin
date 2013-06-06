<?php

?>
<!DOCTYPE html>
<html>
	<head>
	</head>
	
	
	<body>
		<article>
		<section style="float:left; border:0px solid; margin-left:10px;">
			<form action="index.php?content=system" method="get">
				<input type="hidden" name="content" value="system"/>
				<fieldset>
			    	<legend>Filter</legend>
			    	<select name="system_type" size="1">
			    		<?php
			    		if(isset($_GET['system_type'])){
							$nr = $_GET['system_type'];
						} else {
							$nr = -1;
						}
						?>
			    		<option value="0" <?php if($nr == 0) {echo "selected=\"true\"";}?>>alle Systeme anzeigen</option>
			    		<option value="1" <?php if($nr == 1) {echo "selected=\"true\"";}?>>nur Vorrunde</option>
			    		<option value="2" <?php if($nr == 2) {echo "selected=\"true\"";}?>>nur Zwischenrunde</option>
			    		<option value="3" <?php if($nr == 3) {echo "selected=\"true\"";}?>>nur Finalrunde</option>
			    	</select>
			    	<div style="float:right;">
			    	<input type="submit" value="Filtern">
			    	</div>
			    </fieldset>
			</form>
			<form action="" method="get">
				<p>
					<!-- index.php?content=system&function=showsystem -->
					<input type="hidden" name="content" value="system"/>
					<input type="hidden" name="function" value="showsystem"/>
					<?php 
						if(isset($_GET['system_type']) && $_GET['system_type'] != 0){
							echo "<input type=\"hidden\" name=\"system_type\" value=\"{$_GET['system_type']}\"/>";
						}
					?>
				    <select name="system" size="20" style="width:320px">
				    	<?php 
				    		/**
				    		 * zeige alle System aus der DB
				    		 */
				    		if(isset($_GET['system_type']) && $_GET['system_type'] != 0){
				    	 		$nr = $_GET['system_type'];
				    	 		$sql = "SELECT * FROM system WHERE type=$nr ORDER BY type";
				    	 		$result = $dbAccess->performSelectQuery($sql);
				    	 		while($row = mysqli_fetch_array($result)){
				    	 			echo "<option value=\"{$row['nr']}\">{$row['name']} - {$row['nr_of_teams']} Teams - {$row['nr_of_games']} - Spiele</option>";
				    	 		}
							} else { 
								$sql = "SELECT * FROM system ORDER BY type";
								$result = $dbAccess->performSelectQuery($sql);
								while($row = mysqli_fetch_array($result)){
									echo "<option value=\"{$row['nr']}\">{$row['name']} - {$row['nr_of_teams']} Teams - {$row['nr_of_games']} Spiele</option>";
								}
							}
				    	?>
				    </select>
			  	</p>
			  	<input style="width:100%" type="submit" value="System anzeigen">
			</form>
		</section>
		<section style="float:left;border:0px solid; margin-left:50px">
			
			<?php
				if (isset($_GET['function'])){
			 		if($_GET['function'] == "showsystem"){
			 
			 			/**
						 * Algorithmus zur Systemanzeige aus der DB
						 */
						 
			 			if(isset($_GET['system'])){
			 				
							$system = $_GET['system'];

							$sql = "SELECT * FROM system WHERE nr = {$system} ";
							$result = $dbAccess->performSelectQuery($sql);
							$row = mysqli_fetch_array($result);
							
							// SYSTEM
							echo 	"<h3>System: {$system}</h3>";
							echo "<ul>";
							echo "<li>{$row['nr_of_teams']} Mannschaften</li>";
							echo "<li>{$row['nr_of_games']} Spiele</li>";
							echo "</ul>";
							
							// DAUER: Tabelle 
							echo "<h3>Dauer:</h3>";
							echo "<div style=\"margin-left:35px;\">";
							echo "<table border=\"1\"cellpadding=\"7\" cellspacing=\"0\">";
							echo "	<tr>";
							echo "		<th>Minuten pro Spiel</th>";
							for($i=15;$i<50;$i=$i+5){
								echo "	<td align=\"center\">{$i}</td>";
							}
							echo "	</tr><tr>";
							echo "		<th>Minuten Gesamt</th>";
							for($i=15;$i<50;$i=$i+5){
								$min = ($i * $row['nr_of_games']);
								echo "	<td align=\"center\">{$min}</td>";
							}
							echo "	</tr><tr>";
							echo "		<th>Gesamt</th>";
							for($i=15;$i<50;$i=$i+5){
								$min = ($i * $row['nr_of_games']);
								$h = floor($min / 60);
								
								$min = $min % 60;
								echo "	<td align=\"center\">{$h} h {$min} min</td>";
							}
							echo "	</tr>";
							echo "</table>";
							echo "</div>";
							
							// SPIELTABELLE
							echo "<h3>Spiele:</h3>";
							echo "<div style=\"margin-left:35px;\" >";
							echo "<table border=\"1\"cellpadding=\"7\" cellspacing=\"0\">";
							echo "	<tr>";
							echo "		<th>SpielNr.</th>";
							echo "		<th>ID</th>";
							echo "		<th>Mannschaft 1</th>";
							echo "		<th>vs</th>";
							echo "		<th>Mannschaft 2</th>";
							echo "		<th>Schiedsrichter</th>";
							echo "	</tr>";
							
							$sql = "SELECT 	nr,
										(SELECT name FROM slot WHERE slot.nr = game.slot_team1_nr) slot_team1_nr,
										(SELECT name FROM slot WHERE slot.nr = game.slot_team2_nr) slot_team2_nr,
										(SELECT name FROM slot WHERE slot.nr = game.slot_ref_nr) slot_ref_nr
									FROM game
									WHERE sys_nr = {$row['nr']}";
							
							$result = $dbAccess->performSelectQuery($sql);
							
							$i = 0;
							while ($row = mysqli_fetch_array($result)) {
								$i++;
								echo "<tr>";
								echo "	<td align=\"center\">{$i}</td>";
								echo "	<td align=\"center\">{$row['nr']}</td>";
								echo "	<td align=\"center\">{$row['slot_team1_nr']}</td>";
								echo "	<td></td>";
								echo "	<td align=\"center\">{$row['slot_team2_nr']}</td>";
								echo "	<td align=\"center\">{$row['slot_ref_nr']}</td>";
								echo "</tr>";
							}
							echo "</table>";
							echo "</div>";
							
							// ÜBERSICHT:
							echo "<h3>Übersicht:</h3>";
							echo "<div style=\"margin-left:25px;\">";
							echo "	<h4>Legende: <img src=\"res/legende.PNG\" align=\"middle\" /></h4>";
							echo "	<figure><img src=\"res/{$system}.PNG\" /></figure>";
							echo "</div>";
			 				
			 				echo "<br /><br /><br /><br />";
			 				 
			 			}
			 		}	
			 	}
			?>
		</section>
		</article>
	</body>
</html>
