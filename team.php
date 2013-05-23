<?php

?>

<html>
	<head>
		<script type="text/javascript" src="js/team.js"></script>
	</head>
	
	<body>
		<section id="editTeams" style="padding: 10px;">
			<h3>Gew�hlte Mannschaften bearbeiten:</h3>
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
					<td><select data-bind="options: $root.divisions, optionsText: 'name', optionsValue: 'id', value: divisionId"></td>
				</tr>
				</tbody>
			</table>
			<br />
			<button data-bind="click: updateTeamEdit">Speichern</button>&nbsp;&nbsp;
			<button data-bind="click: cancelTeamEdit">Abbrechen</button>
		</section>
		<h3>�bersicht �ber Mannschaften:</h3>
		<span data-bind="visible: teams().length == 0" style="padding: 10px;">Bislang sind noch keine Mannschaften eingetragen...</span>
		<section data-bind="visible: teams().length > 0" style="padding: 10px;">
			<!--ko foreach: divisions()-->
			<div style="border:1px solid; float:left; padding:10px; margin: 15px; border-radius: 7px;">
				<center><!--ko text: name--><!--/ko--></center>
				<hr />
				<table cellspacing="0" cellpadding="5px" border="0">
				<thead>
				<!-- <tr>
					<th />
					<th style="text-align:left">Name</th>
					<th style="text-align:left">Spielklasse</th>
				</tr> -->
				</thead>
				<tbody data-bind="foreach: { data: $root.filteredTeamsByDivision(id), afterAdd: $root.elementFadeIn }">
				<tr>
					<td><input class="teamSelectCheckbox" type="checkbox" data-bind="value: id" onClick="controlOkButton()" /></td>
					<td><span data-bind="text: name"></td>
					<!-- <td><span data-bind="text: divisionName"></td> -->
				</tr>
				</tbody>
				<tr>
					<td colspan="2"><small><a href="#" data-bind="click: $root.selectAllTeams">Alle</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="#" data-bind="click: $root.deselectAllTeams">Keiner</a></small></td>
				</tr>		
			</table>
			</div>
			<!--/ko-->


			
			<!-- <table cellspacing="0" cellpadding="5px" border="0">
				<thead>
				<tr>
					<th />
					<th style="text-align:left">Name</th>
					<th style="text-align:left">Spielklasse</th>
				</tr>
				</thead>
				<tbody data-bind="foreach: { data: teams(), afterAdd: elementFadeIn }">
				<tr>
					<td><input class="teamSelectCheckbox" type="checkbox" data-bind="value: id" onClick="controlOkButton()" /></td>
					<td><span data-bind="text: name"></td>
					<td><span data-bind="text: divisionName"></td>
				</tr>
				</tbody>
				<tr>
					<td colspan="3"><small><a href="#" data-bind="click: selectAllTeams">Alle</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="#" data-bind="click: deselectAllTeams">Keiner</a></small></td>
				</tr>		
			</table> -->
			<br style="clear:both"/>
			Auswahl:&nbsp;&nbsp;
			<select id="editDeleteSelection">
				<option value="edit">bearbeiten</option>
				<option value="delete">l�schen</option>
			</select>&nbsp;
			<button id="OK_Button" data-bind="click: performEditDelete">OK</button><br /> 
		</section>
		<h3><a href="#" data-bind="click: showNewTeams, visible: newTeams().length == 0">Neue Mannschaften anlegen:</a></h3>
		<h3 data-bind="visible: newTeams().length > 0">Neue Mannschaften anlegen:</h3>
		<section id="createTeamSection" style="padding: 10px;" data-bind="if: divisions().length > 0">
			<table cellspacing="0" cellpadding="5px">
				<thead>
				<tr>
					<th style="text-align:left">&nbsp;Name</th>
					<th style="text-align:left">&nbsp;Spielklasse</th>
					<th />
				</tr>
				</thead>
				<tbody data-bind="foreach: { data: newTeams(), afterAdd: elementFadeIn, beforeRemove: elementFadeOut }">
				<tr>
					<td><input data-bind="value: name" placeholder="Name"></td>
					<td><select data-bind="options: $root.divisions, optionsCaption: $root.divisions().length > 1 ? 'Spielklasse w�hlen..' : '', optionsText: 'name', optionsValue: 'id', value: divisionId"></select>
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
		<section id="createTeamSection" style="padding: 10px;" data-bind="if: divisions().length == 0">
		Es sind noch keine Spielklassen angelegt!<br />
		F�r das Eintragen von Mannschaften ist mindestens eine Spielklasse erforderlich..
		</section>
	</body>
</html>
