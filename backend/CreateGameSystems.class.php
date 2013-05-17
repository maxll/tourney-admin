<?php

class CreateGameSystems {

	private $connection = "";
	private $output = "";
	
    function __construct($connection) {
    	
    	$this->connection = $connection;
    	
    	$this->output .= "<ul>";
     	$this->sys_13(0,0);
     	$this->sys_14(0,0);
    	$this->sys_331(0,0);
    	
    	$this->output .= "</ul>";
    }
    
    /*	Vorrunde:
     * 
     * 	1300	-> Jeder gegen Jeden, 3 Teams, 3 Spiele
     * 	1400	-> Jeder gegen Jeden, 4 Teams, 6 Spiele
     *  1500	-> Jeder gegen Jeden, 5 Teams, 10 Spiele
     *  
     *  Zwischenrunde:
     *
     *  262001  -> 6 Teams aus 2 Gruppen, Platz 1 der Gruppen spielen gegen Platz 2 und 3. Platz 2 der Gruppen spielen noch gegen Platz 3.
     *			-> 6 Spiele
     *  263001	-> 6 Teams aus 3 Gruppen, Platz 1 der Gruppen spielen gegen Platz 2 aus der anderen Gruppe
     *  		-> 6 Spiele
     *  
     *  KO-Runde:
     *  
     *  33100	-> 3 Teams, System 1 (WB 3/1)
     *  33200	-> 3 Teams, System 2 (WB 3/2)
     *  34100	-> 4 Teams
     *  bis
     *  34500
     *  35100	-> 5 Teams
     *  36100	-> 6 Teams
     *  bis
     *  36400
     *  38100	-> 8 Teams
     *  bis
     *  38200
     *  
     */
    
    private function insertInto($table, $sql){
    	
    	if (mysqli_query($this->connection, $sql)){
    		$this->output .= "<li>INSERT INTO <strong>$table</strong> was successfull</li>";
    	} else {
    		$this->output .= "<li>Error in INSERT INTO <strong>$table</strong>: " . mysqli_error($this->connection) . "</li>";
    	}
    }
    
    
    
    public function sys_13($nr, $group_id){
    	
    	if($nr <= 9){
    		$nr = "0$nr";
    	}
    	
    	// system
    	$sql = "INSERT INTO system (nr, group_id, name, type, nr_of_teams, nr_of_games) 
    			VALUES (13{$nr}, $group_id, 'Jeder gegen Jeden - 3 Mannschaften -  3 Spiele', 1, 3, 3)";
    	
    	$this->insertInto("system", $sql);
    	
    	// slot
		$sql = "INSERT INTO slot (nr, team_name, name) 
				VALUES  (13{$nr}01, null, 'Team A'),
					 	(13{$nr}02, null, 'Team B'),
						(13{$nr}03, null, 'Team C')";
		
		$this->insertInto("slot", $sql);
		
		// game
		$sql = "INSERT INTO game (nr, sys_nr, overall_nr, slot_team1_nr, slot_team2_nr, slot_ref_nr) 
				VALUES  (13{$nr}1, 13{$nr}, 1, 13{$nr}01, 13{$nr}02, 13{$nr}03),
						(13{$nr}2, 13{$nr}, 2, 13{$nr}03, 13{$nr}01, 13{$nr}02),
						(13{$nr}3, 13{$nr}, 3, 13{$nr}02, 13{$nr}03, 13{$nr}01)";   
		
		$this->insertInto("game", $sql);
    }
    
    
    function sys_14($nr, $group_id){
    	
    	if($nr <= 9){
    		$nr = "0$nr";
    	}
    	
    	// system
    	$sql = "INSERT INTO system (nr, group_id, name, type, nr_of_teams, nr_of_games) 
    			VALUES (14{$nr}, $group_id, 'Jeder gegen Jeden - 4 Mannschaften - 6 Spiele', 1, 4, 6)";
    	
    	$this->insertInto("system", $sql);
    	
    	// slot
		$sql = "INSERT INTO slot (nr, team_name, name) 
				VALUES  (14{$nr}01, null, 'Team A'),
					 	(14{$nr}02, null, 'Team B'),
						(14{$nr}03, null, 'Team C'),
						(14{$nr}04, null, 'Team D')";
		
		$this->insertInto("slot", $sql);
		
		// game
		$sql = "INSERT INTO game (nr, sys_nr, overall_nr, slot_team1_nr, slot_team2_nr, slot_ref_nr) 
				VALUES  (14{$nr}1, 14{$nr}, 1, 14{$nr}01, 14{$nr}02, 14{$nr}04),
						(14{$nr}2, 14{$nr}, 2, 14{$nr}03, 14{$nr}04, 14{$nr}01),
						(14{$nr}3, 14{$nr}, 3, 14{$nr}01, 14{$nr}03, 14{$nr}02),
						(14{$nr}4, 14{$nr}, 4, 14{$nr}02, 14{$nr}04, 14{$nr}03),
						(14{$nr}5, 14{$nr}, 5, 14{$nr}04, 14{$nr}01, 14{$nr}03),
						(14{$nr}6, 14{$nr}, 6, 14{$nr}03, 14{$nr}02, 14{$nr}04)";   
		
		$this->insertInto("game", $sql);
    }
    
    
    function sys_331($nr, $group_id){
    	 
//     	if($nr <= 9){
//     		$nr = "0$nr";
//     	}
    	 
    	// system
    	$sql = "INSERT INTO system (nr, group_id, name, type, nr_of_teams, nr_of_games)
    	VALUES (331{$nr}, $group_id, 'Finalrunde - 3 Mannschaften - 2 Spiele', 3, 3, 2)";
    	 
    	$this->insertInto("system", $sql);
   
    	// slot
    	$sql = "INSERT INTO slot (nr, team_name, name)
    	VALUES  (331{$nr}01, null, 'Team A'),
    			(331{$nr}02, null, 'Team B'),
    			(331{$nr}03, null, 'Team C'),
    			(331{$nr}04, null, 'Gew. Spiel 1'),
    			(331{$nr}05, null, 'Verl. Spiel 1'),
    			(331{$nr}06, null, 'Gew. Spiel 2'),
    			(331{$nr}07, null, 'Verl. Spiel 2')";
    	
    	$this->insertInto("slot", $sql);
    	
    	// game
    	$sql = "INSERT INTO game (nr, sys_nr, overall_nr, slot_team1_nr, slot_team2_nr, slot_ref_nr)
    	VALUES  (331{$nr}1, 331{$nr}, 1, 331{$nr}02, 331{$nr}03, 331{$nr}01),
    			(331{$nr}2, 331{$nr}, 2, 331{$nr}01, 331{$nr}04, 331{$nr}05)";
    	
    	$this->insertInto("game", $sql);
    }
    
    public function __toString(){
    	return $this->output;
    }
}
?>