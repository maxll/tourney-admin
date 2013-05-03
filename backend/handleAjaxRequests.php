<?php

require_once 'MySQLConnector.class.php';

// $dbConnector = new MySQLConnector();
// $connection = $dbConnector->getConnection();

	// echo "GET:";
	// var_dump($_GET);

	// echo "POST:";
	// var_dump($_POST);

	// if(isset($_GET['jsondata'])){
	// 	echo "GET klappt\n";
	// }


	if(isset($_POST)){

		$dbConnector = new MySQLConnector();
		$connection = $dbConnector->getConnection();
		
		foreach ($_POST as $key => $value){

			switch ($key) {
			case 'jsonTeamData':
				insertTeamsInDB($value);
				break;
			default:
				break;
			
			} // switch
		} // foreach
	} // isset
	

	/*
	if (mysqli_query($connection, $sql)){
		//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
	} else {
		echo "<li>Error in INSERT INTO <strong>division</strong>: " . mysqli_error($connection) . "</li>";
		}*/


function insertTeamsInDB($jsonTeamData){

	$teamData = json_decode($jsonTeamData, true);

	// instert-statement
	$sql = "INSERT INTO team (name, div_nr) VALUES";

	// VALUES for each entry in teams
	foreach ($teamData['teams'] as $team){
		
		$sql .= " ({$team['name']}, {$team['division']['divNr']}),";
	}
	
	// delete last comma
	$sql = substr_replace($sql, "", -1);

	if (mysqli_query($connection, $sql)){
		//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
	} else {
		echo "<li>Error in INSERT INTO <strong>division</strong>: " . mysqli_error($connection) . "</li>";
	}
}



	/*$.ajax({
		type: "GET",
		// type: "POST",
		url: "ajaxtest.php",
		data: {jsondata: jsondata},
		contentType: "application/json",
		success: function(data) { alert(data); }
		// error: function(xhr, status, error) { alert("xhr: " + xhr + "\nstatus: " + status + "\nerror: " + error); }
	});*/
?>