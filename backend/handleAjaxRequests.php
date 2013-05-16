<?php

require_once 'MySQL_Access.class.php';

$dbAccess = new MySQL_Access();


	// echo "POST:";
	// var_dump($_POST);

	// echo "GET:";
	// var_dump($_GET);

	/**
	 * check $_GET for loading from database
	 */
	if(isset($_GET)){

		foreach ($_GET as $key => $value){

			switch($key) {
			case 'getAllTeams':
				getAllTeams();
				break;
			case 'getAllDivisionNames':
				getAllDivisionNames();
				break;
			}
		}
	}


	/**
	 * check $_POST for saving to database
	 */
	if(isset($_POST)){

		foreach ($_POST as $key => $value){

			switch($key) {
			case 'insertNewTeams':
				insertTeams($value);
				break;
			case 'deleteTeams':
				deleteTeams($value);
				break;
			case 'updateTeams':
				updateTeams($value);
				break;
			}
		}
	}



function insertTeams($jsonTeamData){

	global $dbAccess;

	$teamData = json_decode($jsonTeamData, true);

	$teamarray = array();
	foreach ($teamData['teams'] as $team){
		$name = mysql_real_escape_string($team['name']);
		array_push($teamarray, array('name' => $name, 'div_id' => $team['divisionId']));
	}

	$dbAccess->insertTeams($teamarray);	

}

function updateTeams($jsonTeamData){

	global $dbAccess;

	$teamData = json_decode($jsonTeamData, true);

	$teamarray = array();
	foreach ($teamData['teams'] as $team){
		array_push($teamarray, array('id' => $team['id'], 'name' => "{$team['name']}", 'div_id' => $team['divisionId']));
	}

	$dbAccess->updateTeams($teamarray);	

}

function deleteTeams($jsonTeamIdData){

	global $dbAccess;

	$teamIDarray = json_decode($jsonTeamIdData, true);

	$dbAccess->deleteTeams($teamIDarray);
}


function getAllTeams(){

	global $dbAccess;
	$teams = array();

	$teams = $dbAccess->selectAllTeams();

	$teams = json_encode($teams);
	
	$teams = json_encode("{ \"teams\" : $teams}");
	
	echo $teams;

}

function getAllDivisionNames(){
	global $dbAccess;

	$divisions = array();

	$divisions = $dbAccess->selectAllDivisionNames();

	$divisions = json_encode($divisions);

	$divisions = json_encode("{ \"divisions\" : $divisions}");

	echo $divisions;
}

?>