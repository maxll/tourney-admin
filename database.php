<?php
/**
 *
 * @author Maximilian Walden
 * @copyright Copyright &copy; 22.03.2013, Maximilian Walden
 * @version 0.1
 */
 
  
// require_once '/class/MySQLConnector.class.php';
require_once '/backend/CreateTables.class.php';
require_once '/backend/CreateGameSystems.class.php';

// $dbConnector = new MySQLConnector();
// $connection = $dbConnector->getConnection();

?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	
	
	<body>
		<div style="float:left;width:100%;border:0px solid;">
		<section style="float:left; border:0px solid; margin-left:10px;">
			<span class="center">Tabellen in Datenbank:</span>
			<form action="index.php?content=database&amp;function=showtable" method="post">
				<p>
				    <select name="table" size="12" style="width:170px">
				    	<?php
				    	
				    		/**
				    		 * print all tables from the database in $dbConnector
				    		 */
				    		 
				    	 	$result = mysqli_query($connection, "SHOW TABLES FROM " . $dbConnector->getDBName());
				    	 	if (!$result) {
				    	 		die("Query to show systems from table failed");
				    	 	}
							while($row = mysqli_fetch_array($result)){
								if($row[0] == "group"){
									echo "<option value=\"`{$row[0]}`\">{$row[0]}</option>";
								} else {
									echo "<option value=\"{$row[0]}\">{$row[0]}</option>";
								}
							}
				    	?>
				    </select>
			  	</p>
			  	<input style="width:100%" type="submit" value="Tabelle anzeigen">
			</form>
			<br />
			<form action="index.php?content=database&amp;function=insertSystems" method="post">
				<input style="width:100%" type="submit" value="Systeme füllen" />
			</form>
			<br />
			<form action="index.php?content=database&amp;function=deleteSystems" method="post">
				<input style="width: 100%" type="submit" value="Systeme löschen" />
			</form>
			<form action="index.php?content=database&amp;function=deleteTeams" method="post">
				<input style="width: 100%" type="submit" value="Mannschaften löschen" />
			</form>
			<form action="index.php?content=database&amp;function=deleteDivisions" method="post">
				<input style="width: 100%" type="submit" value="Spielklassen löschen" />
			</form>
			<br />
			<form action="index.php?content=database&amp;function=buildDb" method="post">
				<input style="width: 100%" type="submit" value="Tabellen neu anlegen" />
 			</form>
		</section>
		<section style="float:left;border:0px solid; margin-left:50px;">
			
			<?php
				// TODO ans löschen einfach die Tablle team / division zeichnen
				if (isset($_GET['function'])){
					switch($_GET['function']){
						case "buildDb": {
							$createTables = new CreateTables($connection);
							echo $createTables;
							break;
						}
						case "deleteTeams":{
							$result = mysqli_query($connection, "DELETE FROM team");
							if (!$result) {
								die("Query to delete teams failed");
							}
							break;
						}
						case "deleteDivisions":{
							$result = mysqli_query($connection, "DELETE FROM division");
							if (!$result) {
								die("Query to delete divisions failed");
							}
							break;
						}
						case "insertSystems": {
							$createGameSystems = new CreateGameSystems($connection);
							echo $createGameSystems;
							break;
						}
						case "deleteSystems": {
							$result = mysqli_query($connection, "DELETE FROM system");
							if (!$result) {
								die("Query to delete system failed");
							}
							$result = mysqli_query($connection, "DELETE FROM game");
							if (!$result) {
								die("Query to delete game failed");
							}
							$result = mysqli_query($connection, "DELETE FROM slot");
							if (!$result) {
								die("Query to delete slot failed");
							}
							break;
						}
						case "showtable": {
							/**
							 * algorithm to print a complete table from DB
							 */
							if(isset($_POST['table'])){
							
								$table = $_POST['table'];
								
								$result = $dbConnector->performSelectQuery("SELECT * FROM $table");
								$fields_num = mysqli_num_fields($result);
							
								echo "<h3>Tabelle: {$table}</h3>";
								echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">";
								echo "<tr>";
									
								// printing table headers
								for($i=0; $i<$fields_num; $i++){
									$field = mysqli_fetch_field($result);
									echo "<th>{$field->name}</th>";
								}
								echo "</tr>";
																	
								// printing table rows
								while($row = mysqli_fetch_row($result)){
											
									echo "<tr>";
							
									// $row is array... foreach( .. ) puts every element
									// of $row to $cell variable
									foreach($row as $cell){
										echo "<td align=\"center\">$cell</td>";
									}
																	
									echo "</tr>";
								}
							}
							break;
						} // end case
					} // end switch
			 	} // end if(isset)
			?>
		
			</section>
		</div>
	</body>

</html>

	

