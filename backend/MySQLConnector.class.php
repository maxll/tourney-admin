<?php

class MySQLConnector {
	
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $database = "schedule";
	private $connection = "";
	
	function __construct(){
		
		/**
		 * Create Connection to MySQL-Database
		 */
		$this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
	
		if (mysqli_connect_errno($this->connection)){
			echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br />";
		}
			
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