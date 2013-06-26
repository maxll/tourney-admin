<?php

interface DataAccess{
	
	public function insertTeams($teams);
	public function updateTeams($teams);
	public function deleteTeams($teams);
	//@return array() of teamData
	public function selectAllTeams();

	
	//@return array() of divisionData
	public function selectAllDivisions();
	
	
	//@return array() of systemData
	public function selectAllSystems();

	
	//@return array() of groupData
	public function selectAllGroups();

	
	//@return array() of stats_per_groupData
	public function selectAllStatsPerGroup();
}


?>