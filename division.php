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
	</head>
	
	
	<body>
		<h3>Übersicht über Spielklassen:</h3>
		<section>
		<?php 
			if(isset($_POST['division'])){

				$sql = "INSERT INTO division (name, game_length, points_win, points_draw, points_defeat) 
 						VALUES ('{$_POST['division']}', 25, 3, 1, 0)";
				
				if (mysqli_query($connection, $sql)){
					//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
				} else {
					echo "<li>Error in INSERT INTO <strong>division</strong>: " . mysqli_error($connection) . "</li>";
				}
			}
			
			$sql = "SELECT * FROM division";
			
			$result = mysqli_query($connection, $sql);
			if (!$result) {
				die("Query to show systems from table failed");
			}
			
			echo "<ul>";
			while($row = mysqli_fetch_assoc($result)){
				echo "<li>{$row['name']}</li>";
			}
			echo "</ul>";
			
		?>
		</section>
		<h3>Neue Spielklasse anlegen:</h3>
		<section>
			<form action="index.php?content=division" method="post">
				<table cellpadding="3px" cellspacing="0">
					<tr>
						<td>Name der Klasse:</td>
						<td><input type="text" name="division" size="20" required="required" placeholder="Herren LK1" /></td>
					</tr>
					<tr>
						<td>Zeit pro Spiel / min:</td>
						<td><input class="input_number" type="text" name="time" size="2" required="required" value="25" /></td>	
					</tr>
					<tr>
						<td>Punkte für Sieg:</td>
						<td><input class="input_number" type="text" name="points_win" size="2" required="required" value="3" /></td>
					</tr>
					<tr>
						<td>Punkte für Unentschieden:&nbsp;&nbsp;</td>
						<td><input class="input_number" type="text" name="points_draw" size="2" required="required" value="1" /></td>
					</tr>
					<tr>
						<td>Punkte für Niederlage:&nbsp;&nbsp;</td>
						<td><input class="input_number" type="text" name="points_defeat" size="2" required="required" value="0" /></td>
					</tr>
					<tr>
						<td colspan="2"><br /><input type="submit" value="Spielklasse erstellen" style="width:100%"/></td>
					</tr>
				</table>
			</form>
		</section>
	</body>
</html>
