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

			}
		}
	}



function insertTeams($jsonTeamData){

	global $dbAccess;

	// decode JSON, fill array, send data
	$teamData = json_decode($jsonTeamData, true);

	$teamarray = array();
	foreach ($teamData['teams'] as $team){
		array_push($teamarray, array('name' => "{$team['name']}", 'div_name' => $team['division']));
	}

	$dbAccess->insertTeams($teamarray);	

}

function deleteTeams($jsonTeamData){

	global $dbAccess;

	// decode JSON, fill array, send data
	$teamData = json_decode($jsonTeamData, true);

	$teamarray = array();
	foreach ($teamData['teams'] as $team){
		array_push($teamarray, array('name' => "{$team['name']}", 'div_name' => $team['division']));
	}

	$dbAccess->deleteTeams($teamarray);	
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