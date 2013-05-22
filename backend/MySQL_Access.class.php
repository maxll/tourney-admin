<?php

require_once 'Data_Access.interface.php';

class MySQL_Access implements Data_Access {
	
	private $host = "127.0.0.1";
	// private $host = "localhost";
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
			$sql .= " ('{$team['name']}', '{$team['div_id']}'),";
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
		/*
		UPDATE team 
		SET name = CASE id 
			WHEN 15 THEN 'KC Wetter1'
		    WHEN 16 THEN 'KC Wetter2'
	    END,
	    div_id = CASE id 
	    	WHEN 15 THEN 1 
	        WHEN 16 THEN 2  
	    END
		WHERE id IN (15, 16)*/

		$name = array();
		$div_id = array();

		foreach($teams as $team){
			$name[$team['id']] = $team['name'];
			$div_id[$team['id']] = $team['div_id'];
		}

		$ids = implode(', ', array_keys($name));

		$sql = "UPDATE team SET name = CASE id ";

		foreach($name as $id => $value){
			$sql .= "WHEN $id THEN '$value' ";
		}
		$sql .= "END, div_id = CASE id ";

		foreach($div_id as $id => $value){
			$sql .= "WHEN $id THEN $value ";
		}
		$sql .= "END WHERE id IN ($ids)";

		// change here and think about log or other error messages
		if (mysqli_query($this->connection, $sql)){
		//echo "<li>INSERT INTO <strong>division</strong> was successfull</li>";
		} else {
			echo "<li>Error in UPDATE <strong>team</strong>: " . mysqli_error($this->connection) . "</li>";
		}
	}

	public function deleteTeams($teamIDs){

		$sql = "DELETE FROM team WHERE ";
		
		foreach($teamIDs as $id){
			$sql .= " (id = $id) or";
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

		$sql = "SELECT id, name, div_id FROM team";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {

			array_push($teams, array(	'id' => $row['id'],
										'name' => $row['name'],
										'div_id' => $row['div_id']));
		} 

		return $teams;
	}


	public function selectAllDivisions(){

		$divisions = array();

		$sql = "SELECT id, name, game_length, points_win, points_draw, points_defeat FROM division";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {	

			array_push($divisions, array(	'id' => $row['id'],
											'name' => $row['name'],
											'game_length' => $row['game_length'],
											'points_win' => $row['points_win'],
											'points_draw' => $row['points_draw'],
											'points_defeat' => $row['points_defeat']));
		}
		
		return $divisions;	
	}


	public function selectAllSystems(){

		$systems = array();

		$sql = "SELECT nr, group_id, name, type, nr_of_games, nr_of_teams FROM system";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {

			array_push($systems, array(	'nr' => $row['nr'],
										'group_id' => $row['group_id'],
										'name' => $row['name'],
										'type' => $row['type'],
										'nr_of_games' => $row['nr_of_games'],
										'nr_of_teams' => $row['nr_of_teams']));
		}

		return $systems;
	}


	public function selectAllGroups() {

		$groups = array();

		$sql = "SELECT id, name, div_id, sys_nr, startgroup, modified_game_length FROM `group`";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {

			array_push($groups, array(	'nr' => $row['nr'],
										'name' => $row['name'],
										'div_id' => $row['div_id'],
										'sys_nr' => $row['sys_nr'],
										'startgroup' => $row['startgroup'],
										'modified_game_length' => $row['modified_game_length']));
		}

		return $groups;
	}


	public function selectAllStatsPerGroup(){

		$stats = array();

		$sql = "SELECT id, team_id, group_id, wins, draws, defeats, points, goals_scored, goals_received, goaldiff, rank
				FROM stats_per_group";

		$result = $this->performSelectQuery($sql);
		while($row = mysqli_fetch_assoc($result)) {

			array_push($stats, array(	'id' => $row['id'],
										'team_id' => $row['team_id'],
										'group_id' => $row['group_id'],
										'wins' => $row['wins'],
										'draws' => $row['draws'],
										'defeats' => $row['defeats'],
										'points' => $row['points'],
										'goals_scored' => $row['goals_scored'],
										'goals_received' => $row['goals_received'],
										'goaldiff' => $row['goaldiff'],
										'rank' => $row['rank']));
		}

		return $stats;
	}

/*
delimiter //
create trigger calculate_stats before insert on stats_per_group
for each row
begin
set new.goaldiff = goals_scored - goals_received;
end;//
*/

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