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
		<script type="text/javascript">
			/*$(document).ready(function(){
				$('#teamForm').validate({
					debug: true
					onfocusout: true
				})
			});*/
		</script>
		<style>
			#divSelector { margin-left: .5em; float: left; }
		  	#divSelector, label { float: left; font-family: Arial, Helvetica, sans-serif; font-size: small; }
			br { clear: both; }
			input { border: 1px solid black; margin-bottom: .5em;  }
			input.error { border: 1px solid red; }
</style>
	</head>
	
	<body>
		<h3>Übersicht über Mannschaften:</h3>
		<section>

		<?php
					
		/* 
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
			<form id="teamForm">
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
					<td><input id="teamName" data-bind="value: name" placeholder="Name" class="required"></td>
					<td><select id="divSelector" data-bind="options: $root.divisions, optionsValue: 'divNr', optionsText: 'divName'" class="required"></select></td>
					<td><a href="#" data-bind="click: $root.removeTeam"><small>Mannschaft löschen</small></a></td>
				</tr>
				</tbody>
			</table>
			<a href="#" data-bind="click: addTeam"><small>neue Mannschaft eintragen..</small></a><br />
			<br />
			<input type="submit" data-bind="value: buttonLabel, enable: teams().length > 0, click: saveTeamsInDB" />
			</form>
		</section>
	</body>
</html>
