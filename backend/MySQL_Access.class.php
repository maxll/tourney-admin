<?php

require_once 'Data_Access.interface.php';

class MySQL_Access implements Data_Access {
	
	private $host = "127.0.0.1";
	private $user = "root";
	private $pass = "";
	private $database = "schedule";
	private $connection = "";
	

	/**
	 * creates connection to MySQL-Database
	 */	
	function __construct(){
		
		$this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
	
		if (mysqli_connect_errno($this->connection)){
			echo "Failed to connect to MySQL-Database: " . mysqli_connect_error() . "<br />";
		}
			
	}

	/**
	 * closes connection to database
	 */
	function __destruct(){
		$this->closeConnection();
	}
	
	/**
	 * inserts all teams in $teams-array into database
	 * @param  array $teams saves team.name and team.div_id
	 */
	public function insertTeams($teams){

		$sql = "INSERT INTO team (name, div_id) VALUES";

		foreach($teams as $team){
			$sql .= " ('{$team['name']}', '{$team['div_name']}'),";
		}

		// delete last comma
		$sql = substr_replace($sql, "", -1);


		// change here and think about log or other error messages
		if (mysqli_query($this->connection, $sql)){
		//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
		} else {
			echo "<li>Error in INSERT INTO <strong>team</strong>: " . mysqli_error($this->connection) . "</li>";
		}		
	}

	public function updateTeams($teams){
		echo "hi";
	}

	public function deleteTeams($teams){

		$sql = "DELETE FROM team WHERE ";
		
		foreach($teams as $team){
			$sql .= " (name = '{$team['name']}' and div_name = '{$team['div_name']}') or";
		}

		// delete last or
		$sql = substr_replace($sql, "", -1);
		$sql = substr_replace($sql, "", -1);
		$sql = substr_replace($sql, "", -1);	

		// change here and think about log or other error messages
		if (mysqli_query($this->connection, $sql)){
		//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
		} else {
			echo "<li>Error in DELTE FROM <strong>team</strong>: " . mysqli_error($this->connection) . "</li>";
		}		
	}

	public function selectAllTeams(){

		$teams = array();

		$sql = "SELECT team.name as teamName, team.id as teamId, team.div_id as teamDiv_id
				FROM team
				LEFT OUTER JOIN division 
				ON team.div_id = division.id";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {

			array_push($teams, array('id' => $row['teamId'], 'name' => $row['teamName'], 'div_id' => $row['teamDiv_id']));
		} 

		return $teams;
	}


	public function selectAllDivisionNames(){

		$divisions = array();

		$sql = "SELECT id, name from division";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {	

			array_push($divisions, array('id' => $row['id'], 'name' => $row['name']));
		}
		
		return $divisions;	
	}


	public function performSelectQuery($sql){
		
		$result = mysqli_query($this->connection, $sql);
		if (!$result) {
			die("Query: \"$sql\" failed");
			echo mysqli_error($connection);
			return $result;
		} 
		return $result;
	}
	
	public function getConnection(){
		return $this->connection;
	}
	
	public function closeConnection(){
		mysqli_close($this->connection);
	}
	
	public function getDBName(){
		return $this->database;
	}
}


?>