<?php

require_once '/backend/MySQL_Access.class.php';

$dbConnector = new MySQL_Access();
$connection = $dbConnector->getConnection();
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- <title>Turnierplan - Verwaltung</title>-->
		<link rel="stylesheet" type="text/css" href="css/general.css" />
		<script type="text/javascript" src="js/knockout-2.2.1.min.js"></script>
		<script type="text/javascript" src="js/knockout-mapping-2.4.1.min.js"></script>
		<script type="text/javascript" src="js/knockout.validation.min.js"></script>
		<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
	</head>
	
	
	<body>
		<nav style="width:100%; height:100px">
			<h3 class="center">Turnierplan - Verwaltung</h3>
			<div style="width:100%; margin-left:10px">
			<?php 
				$sites = array(	"Mannschaften" => "team",
								"Spielklassen" => "division", 
								"Gruppen" => "group",
								"Spielsysteme" => "system",
								"Datenbank" => "database");
				
				foreach($sites as $sitename => $link){
					echo "<span class=\"nav-sitelink\">";
					echo "	<a href=\"index.php?content=$link\">$sitename</a>";
					echo "</span>|";
				}
			?>
			</div>
			<hr />
		</nav>
		
		<article style="width:100%; height:100%; margin-left:10px">
			<?php
				if (isset($_GET['content'])){
					switch($_GET['content']){
						case "team": include("team.php"); break;
						case "division": include ("division.php"); break;
						case "group": include ("group.php"); break;
						case "system": include ("system.php"); break;
						case "database": include ("database.php"); break;
					}
				} else {
					echo "hi";
					//include ("index.php");
				}
			?>
			
		</article>
	</body>
</html>

