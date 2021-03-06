<?php

class MySQLCreateDBStructure {

	private $connection = "";
	private $output = "";

    function __construct($connection) {
    
    	$this->connection = $connection;
    	
 		$this->output = "<h2>Trying to create tables now...</h2>";
 		$this->output .= "<ul>";
 		
        // tables
 		$this->division();
 		$this->team();
 		$this->player();
 		$this->stats_per_group();
 		$this->group();
 		$this->prev_group();
 		$this->groupsystem();
 		$this->game();
 		$this->slot();

        //trigger
        $this->calculate_points_goalDiff_rank();
    	
 		$this->output .= "</ul>";
    }


    private function calculate_points_goalDiff_rank(){

        /*$trigger = "calculate_points_goalDiff_rank";

        $sql = "CREATE TRIGGER $trigger BEFORE INSERT ON stats_per_group
                FOR EACH ROW
                BEGIN
                DECLARE pwin int;
                DECLARE pdraw int;
                DECLARE pdefeat int;
                SELECT division.points_win, division.points_draw, division.points_defeat
                INTO pwin, pdraw, pdefeat
                FROM stats_per_group
                LEFT OUTER JOIN team ON stats_per_group.team_id = team.id
                LEFT OUTER JOIN division ON team.div_id = division.id;
                SET new.points = new.wins * pwin + new.draws * pdraw + new.defeats * pdefeat;
                SET new.goaldiff = new.goals_scored - new.goals_received;
                END";

        $this->dropTrigger($trigger);
        $this->createTrigger($trigger, $sql);*/

        $trigger = "calculate_points_goalDiff_rank_update";

        $sql = "CREATE TRIGGER $trigger BEFORE UPDATE ON stats_per_group
                FOR EACH ROW
                BEGIN
                DECLARE pwin int;
                DECLARE pdraw int;
                DECLARE pdefeat int;
                SELECT division.points_win, division.points_draw, division.points_defeat
                INTO pwin, pdraw, pdefeat
                FROM stats_per_group
                LEFT OUTER JOIN team ON stats_per_group.team_id = team.id
                LEFT OUTER JOIN division ON team.div_id = division.id;
                SET new.points = new.wins * pwin + new.draws * pdraw + new.defeats * pdefeat;
                SET new.goaldiff = new.goals_scored - new.goals_received;
                END";

        $this->dropTrigger($trigger);
        $this->createTrigger($trigger, $sql);
    }
    
    
    private function division(){
    	
    	$table = "division";
    	
    	$sql = "CREATE TABLE $table (
                id INT NOT NULL AUTO_INCREMENT,
     			name varchar(50) NOT NULL UNIQUE,
     			game_duration INT DEFAULT 25,
     			points_win INT DEFAULT 3,
     			points_draw INT DEFAULT 1,
     			points_defeat INT DEFAULT 0,
                PRIMARY KEY(id))";
		
		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function team(){
    	
    	$table = "team";
    	
    	$sql = "CREATE TABLE $table (
                id INT NOT NULL AUTO_INCREMENT,
     			name varchar(50),
     			div_id INT NOT NULL,
                PRIMARY KEY(id),
                UNIQUE(name, div_id))";
    	
		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function player(){
    	
    	$table = "player";
    	
    	$sql = "CREATE TABLE $table (
                id INT NOT NULL AUTO_INCREMENT,
     			firstname varchar(50),
     			name varchar(50),
     			team_id INT NOT NULL,
     			number INT,
     			is_captain TINYINT(1),
                PRIMARY KEY(id),
                UNIQUE(firstname, name))";

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
    	
    	$sql = "CREATE TABLE $table (
                id INT NOT NULL AUTO_INCREMENT,
     			team_id INT NOT NULL,
     			group_id INT NOT NULL,
     			wins INT DEFAULT 0,
     			draws INT DEFAULT 0,
     			defeats INT DEFAULT 0,
     			points INT DEFAULT 0,
     			goals_scored INT DEFAULT 0,
     			goals_received INT DEFAULT 0,
    			goaldiff INT DEFAULT 0, 
                rank INT DEFAULT 0,
                PRIMARY KEY(id), 
                UNIQUE(team_id, group_id))";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }

    
    private function group(){
    	
    	$table = "`group`";
    	
    	$sql = "CREATE TABLE $table (
                id INT NOT NULL AUTO_INCREMENT,
     			name varchar(50),
     			div_id INT NOT NULL,
     			sys_nr INT,
     			startgroup TINYINT(1),
     			modified_game_duration INT DEFAULT null, 
                PRIMARY KEY(id), 
                UNIQUE(name, div_id))";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function prev_group(){
    	 
    	$table = "prev_group";
    	 
    	$sql = "CREATE TABLE $table (
                group_id INT NOT NULL,
                prev_group_id INT NOT NULL,
                position_start INT,
                position_end INT, 
                PRIMARY KEY(group_id, prev_group_id))";
    
    	$this->dropTable($table);
    	$this->createTable($table, $sql);
    }
    
    
    private function groupsystem(){
    	
		$table = "system";
    	
    	$sql = "CREATE TABLE $table (
     			nr INT NOT NULL,
     			PRIMARY KEY(nr),
     			group_id INT,
     			name varchar(50),
     			type INT,
     			nr_of_teams INT,
     			nr_of_games INT)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function game(){
    	
    	/*
    	 * Wenn eine Mannschaft ein Spiel dadurch gewinnt, dass eine andere Mannschaft nicht
		 * antritt oder aufgibt, und eine andere Mannschaft mit der gleichen Punktzahl gegen diese
		 * Verlierermannschaft gespielt hat, dann werden diese beiden Spielergebnisse f�r die
		 * Platzierungsermittlung nicht gewertet --> invalid
    	 */
		$table = "game";
    	
    	$sql = "CREATE TABLE $table (
     			nr INT NOT NULL,
     			PRIMARY KEY(nr),
     			sys_nr INT NOT NULL,
     			overall_nr INT,
     			slot_team1_nr INT NOT NULL,
     			slot_team2_nr INT NOT NULL,
     			slot_ref_nr INT NOT NULL,
     			team1_score INT DEFAULT NULL,
     			team2_score INT DEFAULT NULL,
     			modified_game_duration INT DEFAULT NULL, 
     			invalid TINYINT(1) DEFAULT 0)";

		$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function slot(){
    	
    	$table = "slot";
    	 
    	$sql = "CREATE TABLE $table (
    			nr INT NOT NULL,
    			PRIMARY KEY(nr),
    			team_id INT,
    			name varchar(50))";
    	
    	$this->dropTable($table);
		$this->createTable($table, $sql);
    }
    
    
    private function dropTrigger($trigger){
        if (mysqli_query($this->connection, "DROP TRIGGER IF EXISTS $trigger")){
            $this->output .= "<li>Table <strong>$trigger</strong> dropped successfully</li>";
        } else {
            $this->output .= "<li>Error dropping table <strong>$trigger</strong>: " . mysqli_error($this->connection) . "</li>";
        }
    }

    private function createTrigger($trigger, $sql){
        
        if (mysqli_query($this->connection, $sql)){
            $this->output .= "<li>Trigger <strong>$trigger</strong> is created successfully</li>";
        } else {
            $this->output .= "<li>Error creating trigger <strong>$trigger</strong>: " . mysqli_error($this->connection) . "</li>";
        }
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