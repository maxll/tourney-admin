<?php

interface DataAccess{
	
	public function insertTeams($teams);
	public function updateTeams($teams);
	public function deleteTeams($teams);
	public function selectAllTeams();

	public function selectAllDivisions();
	
	public function selectAllSystems();

	public function selectAllGroups();

	public function selectAllStatsPerGroup();
}


?>