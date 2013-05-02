<?php
/**
 *
 * @author Maximilian Walden
 * @copyright Copyright &copy; 28.03.2013, Maximilian Walden
 * @version 0.1
 */

require_once '/class/MySQLConnector.class.php';

// $dbConnector = new MySQLConnector();
// $connection = $dbConnector->getConnection();

?>

<html>
	<head>
		<script type="text/javascript" src="js/team.js"></script>
	</head>
	
	<body>
		<h3>Übersicht über Mannschaften:</h3>
		<section>
		<?php

			/*
				echo $_POST['teams'];
				echo "<br/>";
				echo stripslashes($_POST['teams']);
				echo "<br/>";
				$data = json_decode(stripslashes($_POST['teams']), true);
				[
				 {
				 	"name":"fasd",
				 	"division":{
						"divName":"Herren LK1"
					}
				 },
				 {"name":"gasd","division":{"divName":"Herren LK2"}},
				 {"name":"","division":{"divName":"Spieklasse wählen"}}
				]
				
				$data = json_decode($_POST['teams'], true);
				echo $data[0]['name'];
				echo "  ";
				echo $data[0]['division']['divName'];
				echo "<br />";
				echo $data[1]['name'];
				echo "  ";
				echo $data[1]['division']['divName'];
			}*/
		/* 
			if(isset($_POST['team_name'])){

				$sql = "INSERT INTO team (name, div_nr) 
 						VALUES ('{$_POST['team_name']}', {$_POST['div_nr']})";
				
				if (mysqli_query($connection, $sql)){
					//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
				} else {
					echo "<li>Error in INSERT INTO <strong>division</strong>: " . mysqli_error($connection) . "</li>";
				}
			}
			
			$sql = "SELECT name,
						(SELECT name FROM division WHERE team.div_nr = division.nr) div_name
					FROM team";
			
			$result = $dbConnector->performSelectQuery($sql);
			
			echo "<ul>";
			while($row = mysqli_fetch_assoc($result)){
				echo "<li>{$row['name']} ({$row['div_name']})</li>";
			}
			echo "</ul>";
		*/	
		?>
		</section>
		<h3>Neue Mannschaft anlegen:</h3>
		<section>

			<!-- <form action="index.php?content=team" method="post"> -->
				
				<table border="0" cellspacing="0" cellpadding="5px">
					<thead>
					<tr>
						<th style="text-align:left">&nbsp;Name</th>
						<th style="text-align:left">&nbsp;Spielklasse</th>
						<th />
					</tr>
					</thead>
					<tbody data-bind="foreach: teams">
					<tr>
						<td><input data-bind="value: name" required="required" placeholder="Name"></td>
						<td><select data-bind="options: $root.divisions, value: division, optionsText: 'divName'"></select></td>
						<td><a href="#" data-bind="click: $root.removeTeam"><small>Mannschaft löschen</small></a></td>
					</tr>
					</tbody>
				</table>
				<a href="#" data-bind="click: addTeam"><small>neue Mannschaft eintragen..</small></a><br />
				<br />
				<!-- <input name="teams" size="80" data-bind="value: ko.toJSON(teams)" /><br /> -->
				<input type="submit" data-bind="value: buttonLabel, enable: teams().length > 0, click: saveTeamsInDB" />
			<!-- </form> -->
		</section>
	</body>
</html>
