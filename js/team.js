
// need for validation
var teamViewModel;

function TeamViewModel(){

	var self = this;

	self.divisions = [];

	self.getAllDivisionNames = function() {

		$.getJSON("backend/handleAjaxRequests.php", {getAllDivisionNames : 1}, function(data) {

			var parsed = JSON.parse(data);

			console.log(parsed.divisions[0].id);
			console.log(parsed.divisions[0].name);
			for(var i = 0; i < parsed.divisions.length; i++){
				self.divisions.push({id: parsed.divisions[i].id, name: parsed.divisions[i].name});
			}

		});
	};


	self.teams = ko.observableArray([]);

	self.getAllTeams = function() {
		self.teams.removeAll();

		$.getJSON("backend/handleAjaxRequests.php", {getAllTeams: 1}, function(data) {

			var parsed =  JSON.parse(data);

			for (var i = 0; i < parsed.teams.length; i++) {
				self.teams.push(new Team(parsed.teams[i].id, parsed.teams[i].name, parsed.teams[i].div_name));
			}
		});
	};
	// -----------------------------------------


	// copy teams, which will be edited
	self.editTeams = ko.observableArray([]);

	// copy teams, which will be deleted
	self.deleteTeams = ko.observableArray([]);

	// filled from checkboxes to get the right teams for edit or delet
	self.teamNrToEditDelete = ko.observableArray([]);

	self.performSelectedChoice = function() {

		if($("#editDeleteSelection").val() == "edit"){
			self.copySelectedInEditTeams();
		} else {
			self.deleteSelectedTeams();
		}
	};

	self.copySelectedInEditTeams = function() {
		/*self.editTeams.removeAll();
		var parsedAll =  JSON.parse(ko.toJSON({teams: self.teams()}));

		for(var i = 0; i < self.teamNrToEditDelete().length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if(self.teamNrToEditDelete()[i] == self.teams()[j].nr){
					self.editTeams.push(new Team(self.teams()[j].nr, parsedAll.teams[j].name, parsedAll.teams[j].division));
				}
			}
		}
		self.teamNrToEditDelete.removeAll();
		$("#editTeams").slideDown();*/
	};

	self.deleteSelectedTeams = function() {
		/*self.deleteTeams.removeAll();
		for(var i = 0; i < self.teamNrToEditDelete().length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if(self.teamNrToEditDelete()[i] == self.teams()[j].nr){
					self.deleteTeams.push(self.teams()[j]);
				}
			}
		}

		var jsonTeamData = ko.toJSON({teams: self.deleteTeams()});

		$.post("backend/handleAjaxRequests.php", {deleteTeams: jsonTeamData}, function(data) {
			self.teamNrToEditDelete.removeAll();
			self.getAllTeams();
		});*/
	};

	self.selectAllTeams = function(){
		$(".teamSelectCheckbox").prop("checked", true);

		/*self.teamNrToEditDelete.removeAll();
		for(var i = 0; i < self.teams().length; i++){
			self.teamNrToEditDelete.push(i);
		}
		$(".teamSelectCheckbox").prop("checked", true);*/
	};

	self.deselectAllTeams = function() {
		$(".teamSelectCheckbox").prop("checked", false);
		// self.teamNrToEditDelete.removeAll();
	};

	self.updateTeamEdit = function() {
		// TODO
		// ajax request senden
		// neuer team name, neue division, alter teamname, alte division über nummer holen, die gleich ist
		// editTeams() sind teams mit name, div und nr
		// über nr den alten namen und alte division aus teams()
	};

	self.cancelTeamEdit = function() {
		$("#editTeams").slideUp(function() {
			// self.editTeams.removeAll();
		});
	};
	// -----------------------------------------


	// newly added teams
	self.newTeams = ko.observableArray([]);
	self.newTeams.extend({ validArray: true });

	self.addNewTeam = function() {
		self.newTeams.push(new Team("", ""));
	};

	self.removeNewTeam = function(team) {
		self.newTeams.remove(team);
	};

	self.clearNewTeams = function() {
		$("#createTeamSection").slideUp(function() {
			self.newTeams.removeAll();
		});
	};

	self.showNewTeams = function() {
		self.newTeams.push(new Team("", ""));
		$("#createTeamSection").slideDown();
	};

	self.saveNewTeamsInDB = function() {

		// got valid form?
		if(teamViewModel.isValid()){

			// team is already in teams()
			if(self.isUniqueTeam()){
				var jsonTeamData = ko.toJSON({teams: self.newTeams()});
				// var jsonTeamData = ko.mapping.toJSON({teams : self.newTeams()});

				$.post("backend/handleAjaxRequests.php", {insertNewTeams: jsonTeamData}, function(data) {
					self.getAllTeams();
					self.clearNewTeams();
				});
			} else {
				alert("Fehler:\nEine oder mehrere Mannschaften sind bereits vorhanden");
			}
		} else {
			alert ("Fehler:\nBitte alle Felder ausfüllen!");
		}
	};
	// -----------------------------------------

	self.isUniqueTeam = function() {

		// check values in Array by converting to JSON and back = ugly.. Solution?
		/*var parsedNew =  JSON.parse(ko.toJSON({teams: self.newTeams()}));
		var parsedAll =  JSON.parse(ko.toJSON({teams: self.teams()}));

		for(var i = 0; i < self.newTeams().length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if((parsedNew.teams[i].name == parsedAll.teams[j].name) && (parsedNew.teams[i].division == parsedAll.teams[j].division)){
					return false;
				}
			}
		}
		return true;*/

		for(var i = 0; i < self.newTeams().length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if(self.newTeams()[i].id == self.teams()[j].id){
					return false;
				}
			}
		}
		return true;
	};

	self.addTeamButtonLabel = ko.computed(function() {
		if(self.newTeams().length > 1){
			return "Mannschaften hinzufügen";
		} else {
			return "Mannschaft hinzufügen";
		}
	});


	// get all divisions from DB with Ajax
	self.getAllDivisionNames();

	// get all teams from DB with Ajax
	self.getAllTeams();
}

function Team(id, name, initialDivision){
	var self = this;
	self.id = id;
	self.name = ko.observable(name).extend({ required: true });
	self.division = ko.observable(initialDivision).extend({ required: true });
}

/*function Division(id, name){
	var self = this;
	self.id = id;
	self.name = name;
}*/


/*function Player(number, firstname, name, captain, teamName, teamDivision){
	var self = this;
	self.number = number;
	self.firstname = firstname;
	self.name = name;
	self.captain = captain;
	self.teamName = teamName;
	self.teamDivision = teamDivision;
}*/


function setupKnockoutValidation(){

	// deactivate error-text-messages, use css-"errorElement" for element-decoration
	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: "errorElement"
	});

	// custom validation Rule
	ko.validation.rules["validArray"] = {
		validator: function (arr, bool) {
			if (!arr || typeof arr !== "object" || !(arr instanceof Array)) {
				throw "[validArray] Parameter must be an array";
			}
			return bool === (arr.filter(function (element) {
				return ko.validation.group(ko.utils.unwrapObservable(element))().length !== 0;
			}).length === 0);
		},
		message: "Every element in the array must validate to '{0}'"
	};

	ko.validation.registerExtenders();
}



// jquery document ready()
$(document).ready(function(){

	$("#editTeams").hide();
	$("#createTeamSection").hide();

	setupKnockoutValidation();

	teamViewModel = ko.validatedObservable(new TeamViewModel());
	ko.applyBindings(teamViewModel);

});