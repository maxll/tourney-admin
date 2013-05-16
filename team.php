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
	</head>
	
	<body>
		<section id="editTeams" style="padding: 10px;">
			<h3>Gewählte Mannschaften bearbeiten:</h3>
				<table border="0" cellspacing="0" cellpadding="5px">
				<thead>
				<tr>
					<th style="text-align:left">&nbsp;Name</th>
					<th style="text-align:left">&nbsp;Spielklasse</th>
				</tr>
				</thead>
				<tbody data-bind="foreach: editTeams()">
				<tr>
					<td><input data-bind="value: name" placeholder="Name"></td>
					<td><select data-bind="options: $root.divisions, value: division"></select></td>
				</tr>
				</tbody>
			</table>
			<br />
			<button>Speichern</button>&nbsp;&nbsp;
			<button data-bind="click: cancelTeamEdit">Abbrechen</button>
		</section>
		<h3>Übersicht über Mannschaften:</h3>
		<span data-bind="visible: teams().length == 0" style="padding: 10px;">Bislang sind noch keine Mannschaften eingetragen...</span>
		<section data-bind="visible: teams().length > 0" style="padding: 10px;">
			<table cellspacing="0" cellpadding="5px" border="0">
				<thead>
				<tr>
					<th />
					<th style="text-align:left">Name</th>
					<th style="text-align:left">Spielklasse</th>
				</tr>
				</thead>
				<tbody data-bind="foreach: teams()">
				<tr>
					<td><input class="teamSelectCheckbox" type="checkbox" data-bind="value: id" /></td>
					<td><span data-bind="text: name"></td>
					<td><span data-bind="text: division"></td>
				</tr>
				</tbody>
				<tr>
					<td colspan="3"><small><a href="#" data-bind="click: selectAllTeams">Alle</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="#" data-bind="click: deselectAllTeams">Keiner</a></small></td>
				</tr>		
			</table>
			<br />
			Auswahl:&nbsp;&nbsp;
			<select id="editDeleteSelection">
				<option value="edit">bearbeiten</option>
				<option value="delete">löschen</option>
			</select>&nbsp;
			<button data-bind="click: performSelectedChoice, enable: teamNrToEditDelete().length > 0">OK</button><br />
		</section>
		<h3><a href="#" data-bind="click: showNewTeams, visible: newTeams().length == 0">Neue Mannschaften anlegen:</a></h3>
		<h3 data-bind="visible: newTeams().length > 0">Neue Mannschaften anlegen:</h3>
		<section id="createTeamSection" style="padding: 10px;">
			<table cellspacing="0" cellpadding="5px">
				<thead>
				<tr>
					<th style="text-align:left">&nbsp;Name</th>
					<th style="text-align:left">&nbsp;Spielklasse</th>
					<th />
				</tr>
				</thead>
				<tbody data-bind="foreach: newTeams()">
				<tr>
					<td><input data-bind="value: name" placeholder="Name"></td>
					<td><select data-bind="options: $root.divisions, optionsCaption: 'Spielklasse wählen..', optionsText: 'name', optionsValue: 'id'"></select>
					</td>
					<td><a href="#" data-bind="click: $root.removeNewTeam"><small>&raquo;&nbsp;entfernen</small></a></td>
				</tr>
				</tbody>
			</table>
			<a href="#" data-bind="click: addNewTeam"><small>&raquo;&nbsp;neue Mannschaft eintragen..</small></a><br />
			<br />
			<button data-bind="click: saveNewTeamsInDB, enable: newTeams().length > 0"><!--ko text: addTeamButtonLabel--><!--/ko--></button>&nbsp;&nbsp;
			<button data-bind="click: clearNewTeams">Abbrechen</button>
		</section>
	</body>
</html>
