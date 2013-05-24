<?php

require_once 'MySQLAccess.class.php';

$dbAccess = new MySQLAccess();


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
				// getAllTeams();
				getData('teams');
				break;
			case 'getAllDivisions':
				// getAllDivisions();
				getData('divisions');
				break;
			case 'getAllSystems':
				// getAllSystems();
				getData('systems');
				break;
			case 'getAllGroups':
				// getAllGroups();
				getData('groups');
				break;
			case 'getAllStats':
				// getAllStats();
				getData('stats');
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


/*function getAllTeams(){

	global $dbAccess;
	$teams = array();

	$teams = $dbAccess->selectAllTeams();

	$teams = json_encode($teams);	
	$teams = json_encode("{ \"teams\" : $teams}");
	
	echo $teams;

}

function getAllDivisions(){
	
	global $dbAccess;
	$divisions = array();

	$divisions = $dbAccess->selectAllDivisions();

	$divisions = json_encode($divisions);
	$divisions = json_encode("{ \"divisions\" : $divisions}");

	echo $divisions;
}


function getAllSystems(){
	global $dbAccess;
	$systems = array();

	$systems = $dbAccess->selectAllSystems();

	$systems = json_encode($systems);
	$systems = json_encode("{ \"systems\" : $systems}");

	echo $systems;
}


function getAllGroups(){

	global $dbAccess;
	$groups = array();

	$groups = $dbAccess->selectAllGroups();

	$groups = json_encode($groups);
	$groups = json_encode("{ \"groups\" : $groups}");

	echo $groups;
}

function getAllStats(){

	global $dbAccess;
	$stats = array();

	$stats = $dbAccess->selectAllStatsPerGroup();

	$stats = json_encode($stats);
	$stats = json_encode("{ \"stats\" : $stats}");

	echo $stats;
}*/

function getData($dataName){

	global $dbAccess;
	$return = array();

	switch ($dataName) {
		case 'teams':
			$return = $dbAccess->selectAllTeams();
			break;
		case 'divisions':
			$return = $dbAccess->selectAllDivisions();
			break;
		case 'systems':
			$return = $dbAccess->selectAllSystems();
			break;
		case 'groups':
			$return = $dbAccess->selectAllGroups();
			break;
		case 'stats':
			$return = $dbAccess->selectAllStatsPerGroup();
			break;
	}

	$return = json_encode($return);
	$return = json_encode("{ \"$dataName\" : $return}");

	echo $return;
}

?>