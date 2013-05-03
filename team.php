<?php
/**
 *
 * @author Maximilian Walden
 * @copyright Copyright &copy; 28.03.2013, Maximilian Walden
 * @version 0.1
 */

?>

<html>
	<head>
		<script type="text/javascript" src="js/team.js"></script>
		<style type="text/css">
			.errorElement { 
				-webkit-box-shadow: 0px 0px 2px 1px red;
				box-shadow: 0px 0px 2px 1px red;
			}
		</style>
	</head>
	
	<body>
		<h3>Übersicht über Mannschaften:</h3>
		<section>
		<?php
					
			$sql = "SELECT name,
						(SELECT name FROM division WHERE team.div_nr = division.nr) div_name
					FROM team";
			
			$result = $dbConnector->performSelectQuery($sql);
			
			echo "<ul>";
			while($row = mysqli_fetch_assoc($result)){
				echo "<li>{$row['name']} ({$row['div_name']})</li>";
			}
			echo "</ul>";
		
		?>
		</section>
		<h3>Neue Mannschaften anlegen:</h3>
		<section>
			<!-- <form id="teamForm"> -->
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
					<td><input data-bind="value: name" placeholder="Name"></td>
					<td><select data-bind="value: division, options: $root.divisions, optionsText: 'divName', optionsCaption: 'Spielklasse wählen..'"></select></td>
						<!-- optionsValue: 'divNr', -->
					<td><a href="#" data-bind="click: $root.removeTeam"><small>entfernen</small></a></td>
				</tr>
				</tbody>
			</table>
			<a href="#" data-bind="click: addTeam"><small>neue Mannschaft eintragen..</small></a><br />
			<br />
			<button data-bind="click: saveTeamsInDB, enable: teams().length > 0"><!--ko text: buttonLabel--><!--/ko--></button>
			<!-- </form> -->
		</section>
	</body>
</html>
