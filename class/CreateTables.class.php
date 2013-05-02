<?php

class CreateTables {

	private $connection = "";
	private $output = "";

    function __construct($connection) {
    
    	$this->connection = $connection;
    	
 		$this->output = "<h2>Trying to create tables now...</h2>";
 		$this->output .= "<ul>";
 		
 		$this->division();
 		$this->team();
 		$this->player();
 		$this->stats_per_group();
 		$this->group();
 		$this->prev_group();
 		$this->groupsystem();
 		$this->game();
 		$this->slot();
 		
 		$this->dropTable("groups");
    	
 		$this->output .= "</ul>";
    }
    
    
    private function division(){
    	
    	$table = "division";
    	
    	$sql = "CREATE TABLE $table (" .
 			"nr INT NOT NULL UNIQUE AUTO_INCREMENT," .
 			"PRIMARY KEY(nr)," .
 			"name varchar(50) UNIQUE," .
 			"game_length INT DEFAULT 25," .
 			"points_win INT DEFAULT 3," .
 			"points_draw INT DEFAULT 1," .
 			"points_defeat INT DEFAULT 0," .
 			"tournament_id INT NOT NULL)";
		
		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function team(){
    	
    	$table = "team";
    	
    	$sql = "CREATE TABLE $table (" .
 			"name varchar(50) NOT NULL," .
 			"div_nr INT NOT NULL," .
 			"PRIMARY KEY(name, div_nr))";
    	
		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function player(){
    	
    	$table = "player";
    	
    	$sql = "CREATE TABLE $table (" .
 			"firstname varchar(50) NOT NULL," .
 			"name varchar(50) NOT NULL," .
 			"PRIMARY KEY(name, firstname)," .
 			"team_name varchar(50)," .
 			"number INT," .
 			"is_captain TINYINT(1))";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function stats_per_group(){
    	/*
    	 * Haben zwei oder mehrere Mannschaften die gleiche Punktanzahl erzielt, so wird die
		 * Platzierung wie folgt ermittelt:
		 * a) Tordifferenz
         * b) erzielte Tore
         * c) Ergebnis des von den beiden Mannschaften ausgetragenen Gruppenspiels
    	 */
    	$table = "stats_per_group";
    	
    	$sql = "CREATE TABLE $table (" .
 			"team_name varchar(50) NOT NULL," .
 			"group_name varchar(50) NOT NULL," .
 			"div_nr INT NOT NULL," .
 			"PRIMARY KEY(team_name, group_name, div_nr)," .
 			"wins INT DEFAULT 0," .
 			"draws INT DEFAULT 0," .
 			"defeats INT DEFAULT 0," .
 			"points INT DEFAULT 0," .
 			"goals_scored INT DEFAULT 0," .
 			"golas_received INT DEFAULT 0," .
			"goaldiff INT DEFAULT 0)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function group(){
    	
    	$table = "`group`";
    	
    	$sql = "CREATE TABLE $table (" .
 			"name varchar(50) NOT NULL," .
 			"div_nr INT," .
 			"PRIMARY KEY(name, div_nr)," .
 			"sys_nr INT," .
 			"startgroup TINYINT(1)," .
 			"modified_game_length INT DEFAULT null)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function prev_group(){
    	 
    	$table = "prev_group";
    	 
    	$sql = "CREATE TABLE $table (" .
    	"group_name varchar(50) NOT NULL," .
    	"prev_group_name varchar(50) NOT NULL," .
    	"div_nr INT NOT NULL," .
    	"PRIMARY KEY(group_name, prev_group_name, div_nr)," .
    	"position_start INT," .
    	"position_end INT)";
    
    	$this->dropTable($table);
    	$this->createTable($table, $sql);
    }
    
    
    private function groupsystem(){
    	
		$table = "system";
    	
    	$sql = "CREATE TABLE $table (" .
 			"nr INT NOT NULL UNIQUE," .
 			"PRIMARY KEY(nr)," .
 			"group_nr INT," .
 			"name varchar(50)," .
 			"type INT," .
 			"nr_of_teams INT," .
 			"nr_of_games INT)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function game(){
    	
    	/*
    	 * Wenn eine Mannschaft ein Spiel dadurch gewinnt, dass eine andere Mannschaft nicht
		 * antritt oder aufgibt, und eine andere Mannschaft mit der gleichen Punktzahl gegen diese
		 * Verlierermannschaft gespielt hat, dann werden diese beiden Spielergebnisse für die
		 * Platzierungsermittlung nicht gewertet --> invalid
    	 */
		$table = "game";
    	
    	$sql = "CREATE TABLE $table (" .
 			"nr INT NOT NULL UNIQUE," .
 			"PRIMARY KEY(nr)," .
 			"sys_nr INT NOT NULL," .
 			"overall_nr INT,".
 			"slot_team1_nr INT NOT NULL," .
 			"slot_team2_nr INT NOT NULL," .
 			"slot_ref_nr INT NOT NULL," .
 			"team1_score INT DEFAULT 0," .
 			"team2_score INT DEFAULT 0," .
 			"modified_game_length INT DEFAULT NULL," . 
 			"invalid TINYINT(1) DEFAULT 0)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function slot(){
    	
    	$table = "slot";
    	 
    	$sql = "CREATE TABLE $table (" .
    			"nr INT NOT NULL UNIQUE," .
    			"PRIMARY KEY(nr)," .
    			"team_name varchar(50)," .
    			"name varchar(50))";
    	
    	$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function dropTable($table){
    	
    	if (mysqli_query($this->connection, "DROP TABLE IF EXISTS $table")){
			$this->output .= "<li>Table <strong>$table</strong> dropped successfully</li>";
		} else {
		  	$this->output .= "<li>Error dropping table <strong>$table</strong>: " . mysqli_error($this->connection) . "</li>";
		}
    }
    
    private function createTable($table, $sql){
    	
    	if (mysqli_query($this->connection, $sql)){
			$this->output .= "<li>Table <strong>$table</strong> is created successfully</li>";
		} else {
		  	$this->output .= "<li>Error creating table <strong>$table</strong>: " . mysqli_error($this->connection) . "</li>";
		}
    }
    
    public function __toString(){
    	return $this->output;
    }
    
}
?>