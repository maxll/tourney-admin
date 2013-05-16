<?php

interface Data_Access{
	
	public function insertTeams($teams);
	public function updateTeams($teams);
	public function deleteTeams($teams);
	public function selectAllTeams();

	public function selectAllDivisionNames();
}


?>