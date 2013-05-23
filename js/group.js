
// need for validation
var groupViewModel;

function GroupViewModel(){

	var self = this;

	// Divisions
	// ----------
	self.divisions = ko.observableArray([]);

	self.selectedDivision = ko.observable();

	self.getAllDivisionNames = function() {
		self.divisions.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllDivisionNames : 1}, function(data) {

			var parsed = JSON.parse(data);

			for(var i = 0; i < parsed.divisions.length; i++){
				self.divisions.push({id: parsed.divisions[i].id, name: parsed.divisions[i].name});
			}
		});
	};

	// Teams
	// ----------
	self.teams = ko.observableArray([]);

	self.getAllTeams = function() {
		self.teams.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllTeams: 1}, function(data) {

			var parsed =  JSON.parse(data);

			for (var i = 0; i < parsed.teams.length; i++) {
				self.teams.push({ id: parsed.teams[i].id, name: parsed.teams[i].name, div_id: parsed.teams[i].div_id});
			}
			console.log(self.teams()[1].name);
		});
	};

	self.filteredTeamsByDivision = function(div_id) {
		return ko.utils.arrayFilter(self.teams(), function(team) {
			return (team.div_id == div_id);
		});
	};

	// Systems
	// ----------

	self.systems = ko.observableArray([]);

	self.selectedSystem = ko.observable();

	self.filteredSystemByNr = function(sys_nr) {
		return ko.utils.arrayFilter(self.systems(), function(system){
			return (system.nr == sys_nr);
		});
	};

	self.filteredSystemByNrOfTeams = function(nr_of_teams) {
		return ko.utils.arrayFilter(self.systems(), function(system){
			return (system.nr_of_teams == nr_of_teams);
		});
	};

	self.getAllSystems = function() {
		self.systems.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllSystems: 1}, function(data) {

			var parsed = JSON.parse(data);

			for (var i = 0; i < parsed.systems.length; i++) {
				self.systems.push({ nr: parsed.systems[i].nr,
									group_id: parsed.systems[i].group_id,
									name: parsed.systems[i].name,
									type: parsed.systems[i].type,
									nr_of_games: parsed.systems[i].nr_of_games,
									nr_of_teams: parsed.systems[i].nr_of_teams });
			}
		});
	};


	// Groups
	// ----------

	self.groups = ko.observableArray([]);

	self.groupSize = ko.computed(function() {
		return self.groups().length;
	});

	self.getAllGroups = function() {
		self.groups.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllGroups : 1}, function(data){

			var parsed = JSON.parse(data);

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


	// Control UI
	// ----------
	self.checkedTeams = ko.observableArray([]);

	self.checkedTeamSize = ko.computed(function() {
		return self.checkedTeams().length;
	});


	// check:
	// available team: team, that does not belong to starting group yet --> check if there is stats_per_group with team_id
	// or set a column in team --> inStartGroup

	self.startGroupCheckState = ko.observable("yes");

	self.requirementsFulfilled = ko.computed(function(){
		if(self.divisions().length === 0){
			return false;
		}

		if((self.startGroupCheckState() == "yes") && (self.filteredTeamsByDivision(self.selectedDivision()).length < 3)){
			return false;
		}

		return true;
	});


	// fill model
	self.getAllDivisionNames();
	self.getAllSystems();
	self.getAllGroups();

	self.getAllTeams();

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

