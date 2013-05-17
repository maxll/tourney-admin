<?php

?>
<!DOCTYPE html>
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
		<section data-bind="visible: teams().length > 0">

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
							<select>
								<option>Herren LK1</option>
								<option>Damen LK1</option>
								<option>Junioren</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Einstiegsgruppe:</td>
						<td><input type="radio" name="startgroup" checked/>ja&nbsp;<input type="radio" name="startgroup" />nein</td>
					</tr>
					<tr>
						<td>Zusammensetzung:</td>
						<td>
							Plätze <input class="input_number" type="text" size="1" value="1"/>
							bis <input class="input_number" type="text" size="1" value="2" />&nbsp;
							aus den Gruppen:
							&nbsp;<input type="checkbox">A</input>
							&nbsp;<input type="checkbox">B</input>
							&nbsp;<input type="checkbox">C</input>
							</ul>
						</td>
					</tr>
					<tr>
						<td>Spielsystem:</td>
						<td>
							<select>
								<option>Jeder gegen Jeden</option>
								<option>Zwischenrunde</option>
								<option>Finalrunde</option>
							</select>
							&nbsp;<small>(x Spiele)</small>
						</td>
					</tr>
					<tr style="vertical-align: top">
						<td>Auswahl Mannschaften:</td>
						<td>
							<input type="checkbox">KC Wetter</input><br />
							<input type="checkbox">WSF Liblar</input><br />
							<input type="checkbox">KGW Essen</input><br />
						</td>
					</tr>
					<tr style="vertical-align: bottom">
						<td>abweichende Spielzeit<br /> für diese Gruppe:</td>
						<td><input type="radio" name="differentlength" />ja&nbsp;
							<input type="radio" name="differentlength" checked />nein
							&nbsp;&nbsp;<input class="input_number" type="text" size="2" value="25" /></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			
		<section>					
			<button>Gruppe anlegen</button>
			<p><br /><br /><br /></p>
		</section>
	</body>
</html>
