
// need for validation
var groupViewModel;

function GroupViewModel(){

	var self = this;

	// divisions
	// ----------
	self.divisions = ko.observableArray([]);

	self.selectedDivision = ko.observable();

	self.getAllDivisions = function() {
		self.divisions.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllDivisions : 1}, function(returnedData) {

			var parsed = JSON.parse(returnedData);

			for(var i = 0; i < parsed.divisions.length; i++) {
				self.divisions.push({id: parsed.divisions[i].id, name: parsed.divisions[i].name});
			}
		});
	};

	// teams
	// ----------
	self.teams = ko.observableArray([]);

	self.getAllTeams = function() {
		self.teams.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllTeams: 1}, function(returnedData) {

			var parsed =  JSON.parse(returnedData);

			for (var i = 0; i < parsed.teams.length; i++) {
				self.teams.push(new Team(parsed.teams[i].id, parsed.teams[i].name, parsed.teams[i].div_id));
			}
		});
	};

	self.filterAvailableTeamsByDivision = function(div_id) {
		return ko.utils.arrayFilter(self.teams(), function(team) {
			return (team.div_id == div_id && team.inStartGroup() === false);
		});
	};



	// systems
	// ----------

	self.systems = ko.observableArray([]);

	self.selectedSystem = ko.observable();

	self.filterSystemByNr = function(sys_nr) {
		return ko.utils.arrayFilter(self.systems(), function(system){
			return (system.nr == sys_nr);
		});
	};

	self.filterSystemByNrOfTeams = function(nr_of_teams) {
		return ko.utils.arrayFilter(self.systems(), function(system){
			return (system.nr_of_teams == nr_of_teams);
		});
	};

	self.getAllSystems = function() {
		self.systems.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllSystems: 1}, function(returnedData) {

			var parsed = JSON.parse(returnedData);

			for (var i = 0; i < parsed.systems.length; i++) {
				if(Number(parsed.systems[i].group_id) === 0){
					self.systems.push({ nr: parsed.systems[i].nr,
										group_id: parsed.systems[i].group_id,
										name: parsed.systems[i].name,
										type: parsed.systems[i].type,
										nr_of_games: parsed.systems[i].nr_of_games,
										nr_of_teams: parsed.systems[i].nr_of_teams });
				}
			}
		});
	};


	// groups
	// ----------

	self.groups = ko.observableArray([]);

	self.groupSize = ko.computed(function() {
		return self.groups().length;
	});

	self.getAllGroups = function() {
		self.groups.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllGroups : 1}, function(returnedData){

			var parsed = JSON.parse(returnedData);

			for(var i = 0; i < parsed.groups.length; i++) {
				self.groups.push({	id: parsed.groups[i].id,
									name: parsed.groups[i].name,
									div_id: parsed.groups[i].div_id,
									sys_nr: parsed.groups[i].sys_nr,
									startgroup: parsed.groups[i].startgroup,
									modified_game_length: parsed.groups[i].modified_game_length });
			}
		});
	};


	// stats_per_group
	// -----------------

	self.stats = ko.observableArray([]);

	self.getAllStats = function() {
		self.stats.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllStats : 1}, function(returnedData){

			var parsed = JSON.parse(returnedData);

			for(var i = 0; i < parsed.stats.length; i++) {
				self.stats.push({	id: parsed.stats[i].id,
									team_id: parsed.stats[i].team_id,
									group_id: parsed.stats[i].group_id });
			}
		});
	};


	// prev_groups
	// --------------

	self.prevGroups = ko.observableArray([]);

	self.getAllPrevGroups = function() {
		self.prevGroups.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllPrevGroups : 1}, function(returnedData){

			var parsed = JSON.parse(returnedData);

			for(var i = 0; i < parsed.prevGroups.length; i++) {
				self.prevGroups.push({	group_id: parsed.prevGroups[i].group_id,
										prev_group_id: parsed.prevGroups[i].prev_group_id,
										position_start: parsed.prevGroups[i].position_start,
										position_end: parsed.prevGroups[i].position_end});
			}
		});
	};


	/*
	
	insert new group: name, division, system, startgroup, modified game duration
	for each team: (id aus checkedTeams())
		insert stats_per_group: team_id, group_id
	insert system with nr => select count(*) from system where nr like "13xx" / "14xx"...

	*/

	self.saveGroup = function(){

		// insert new group and return id for other tables

		var jsonGoupData = ko.toJSON({newGroups: self.newGroups()});

		$.post("backend/handleAjaxRequests.php", {insertNewGroup: jsonGroupData}, function(returnedData) {

		});
	};



	// Control UI
	// ----------
	self.checkedTeams = ko.observableArray([]);

	self.checkedTeamSize = ko.computed(function() {
		return self.checkedTeams().length;
	});

	self.startGroupCheckState = ko.observable("yes");

	self.requirementsFulfilled = ko.computed(function(){
		if(self.divisions().length === 0){
			return false;
		}

		if((self.startGroupCheckState() == "yes") && (self.filterAvailableTeamsByDivision(self.selectedDivision()).length < 3)){
			return false;
		}

		if(self.checkedTeams().length < 3){
			return false;
		}

		return true;
	});



	// fill model
	self.getAllDivisions();
	self.getAllSystems();
	self.getAllGroups();

	self.getAllStats();
	self.getAllTeams();

}


function Team(id, name, div_id){
	var self = this;

	self.id = ko.observable(id);
	self.name = name;
	self.div_id = div_id;
	self.inStartGroup = ko.computed(function() {

		for(var i = 0; i < groupViewModel().stats().length; i++){
			if(self.id() == groupViewModel().stats()[i].team_id) {
				return true;
			}
		}
		return false;
	});
}



// jquery document ready()
$(document).ready(function(){

	// setupKnockoutValidation();

	groupViewModel = ko.validatedObservable(new GroupViewModel());
	ko.applyBindings(groupViewModel);

	// set alternative game duration
	$("#durationYes").click(function() {
		$("#durationMin").prop("disabled", false);
	});
	$("#durationNo").click(function() {
		$("#durationMin").prop("disabled", true);
	});


	// show / hide team selection up on startgroup radio
	$("#teamCompound").hide();
	$("#startGroupYes").click(function() {
		$("#teamCompound").fadeOut(function() {
			$("#teamSelection").fadeIn("slow");
		});

		// nur system jeder ggn jeden 
	});
	$("#startGroupNo").click(function() {
		$("#teamSelection").fadeOut(function() {
			$("#teamCompound").fadeIn("slow");
		});

		// alle systeme
	});

});





/*
<!--ko foreach: teams-->
	<!--ko if: div_id == $parent.selectedDivision()-->
	<input type="checkbox"><!--ko text: name--><!--/ko--></input><br />
	<!--/ko-->
<!--/ko-->


<!--ko foreach: filteredTeamsByDivision(selectedDivision())-->
					
	<input type="checkbox"><!--ko text: name--><!--/ko--></input><br />
	
<!--/ko-->

*/

