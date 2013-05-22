<?php

?>

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
		<section>

			<table cellspacing="0" cellpadding="5px" border="0">
				<thead>
				</thead>
				<tbody>
					<tr>
						<td>Name der Gruppe:</td>
						<td><input type="text" placeholder="Name"/></td>
					</tr>
					<tr>
						<td>Spielklasse:</td>
						<td>
							<select data-bind="options: divisions, optionsValue: 'id',  optionsText: 'name', value: selectedDivision, event: { onchange: checkedTeams.removeAll() }"></select>
						</td>
					</tr>
					<tr>
						<td>Einstiegsgruppe:</td>
						<td>
							<input type="radio" id="startGroupYes" name="startGroup" value="yes" data-bind="checked: startGroupCheckState"/>ja&nbsp;
							<input type="radio" id="startGroupNo" name="startGroup" value="no" data-bind="checked: startGroupCheckState, enable: groupSize > 0"/>nein
						</td>
					</tr>
					<tr id="teamCompound">
						<td>Zusammensetzung:</td>
						<td>
							Plätze <input class="input_number" type="text" size="1" value="1"/>
							bis <input class="input_number" type="text" size="1" value="2" />&nbsp;
							aus den Gruppen:
							&nbsp;<input type="checkbox">A</input>
							&nbsp;<input type="checkbox">B</input>
							&nbsp;<input type="checkbox">C</input>
						</td>
					</tr>
					<tr id="teamSelection" style="vertical-align: top">
						<td>Auswahl Mannschaften:</td>
						<td>
							<!--ko foreach: filteredTeamsByDivision(selectedDivision())-->
								<input type="checkbox" data-bind="value: $index, checked: $root.checkedTeams"><!--ko text: name--><!--/ko--></input><br />
							<!--/ko-->
						</td>
					</tr>
					<tr>
						<td>Spielsystem:</td>
						<td>
							<select data-bind="options: filteredSystemByNrOfTeams(checkedTeamSize()), optionsValue: 'nr', optionsText: 'name', optionsCaption: 'Spielsystem wählen..', value: selectedSystem"></select>&nbsp;
							<small>
								<!--ko foreach: filteredSystemByNr(selectedSystem())-->
									(<!--ko text: nr_of_games--><!--/ko--> Spiele)
								<!--/ko-->
							</small>
						</td>
					</tr>
					<tr style="vertical-align: bottom">
						<td>abweichende Spielzeit<br /> für diese Gruppe:</td>
						<td>
							<input type="radio" id="durationYes" name="differentlength" />ja&nbsp;
							<input type="radio" id="durationNo" name="differentlength" checked />nein
							&nbsp;&nbsp;<input class="input_number" id="durationMin" type="text" size="2" value="25" disabled />&nbsp;Minuten
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</section>	
		<section>					
			<button data-bind="enable: requirementsFulfilled">Gruppe anlegen</button>
			<p><br /><br /><br /></p>
		</section>
	</body>
</html>