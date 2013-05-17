
// need for validation
var teamViewModel;

function TeamViewModel(){

	var self = this;

	self.divisions = [];

	self.getAllDivisionNames = function() {

		$.getJSON("backend/handleAjaxRequests.php", {getAllDivisionNames : 1}, function(data) {

			var parsed = JSON.parse(data);

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
				self.teams.push(new Team(parsed.teams[i].team_id, parsed.teams[i].team_name, parsed.teams[i].div_name));
			}
			self.deselectAllTeams();
		});
	};
	// -----------------------------------------

	self.performEditDelete = function() {

		if($("#editDeleteSelection").val() == "edit"){
			self.copySelectedInEditTeams();
		} else {
			self.deleteSelectedTeams();
		}
	};

	// copy teams, which will be edited
	self.editTeams = ko.observableArray([]);

	self.copySelectedInEditTeams = function() {
		self.editTeams.removeAll();

		var teamIDs = [];
		$(".teamSelectCheckbox:checked").each(function() {
			teamIDs.push($(this).val());
		});

		for(var i = 0; i < teamIDs.length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if(teamIDs[i] == self.teams()[j].id){
					self.editTeams.push(new Team(self.teams()[j].id, self.teams()[j].name(), self.teams()[j].divisionId()));
				}
			}
		}
		$("#editTeams").slideDown();
	};

	self.deleteSelectedTeams = function() {

		var teamIDs = [];
		$(".teamSelectCheckbox:checked").each(function() {
			teamIDs.push($(this).val());
		});

		var jsonTeamData = ko.toJSON(teamIDs);

		self.deselectAllTeams();
		$("#editDeleteSelection").val("edit");

		$.post("backend/handleAjaxRequests.php", {deleteTeams: jsonTeamData}, function(data) {
			self.getAllTeams();
		});
	};

	self.updateTeamEdit = function() {

		var jsonTeamData = ko.toJSON({teams: self.editTeams()});

		$.post("backend/handleAjaxRequests.php", {updateTeams: jsonTeamData}, function(data) {
			self.editTeams.removeAll();
			$("#editTeams").slideUp();
			self.getAllTeams();
		});
	};

	self.selectAllTeams = function(){
		$(".teamSelectCheckbox").prop("checked", true);
		$("#OK_Button").prop("disabled", false);
	};
	self.deselectAllTeams = function() {
		$(".teamSelectCheckbox").prop("checked", false);
		$("#OK_Button").prop("disabled", true);
	};
	self.cancelTeamEdit = function() {
		$("#editTeams").slideUp(function() {
			self.editTeams.removeAll();
		});
		self.deselectAllTeams();
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
		self.newTeams.push(new Team("", "", ""));
		$("#createTeamSection").slideDown();
	};

	self.saveNewTeamsInDB = function() {

		// got valid form?
		if(teamViewModel.isValid()){

			// team is already in teams()
			if(self.isUniqueTeam()){

				var jsonTeamData = ko.toJSON({teams: self.newTeams()});

				$.post("backend/handleAjaxRequests.php", {insertNewTeams: jsonTeamData}, function(data) {
					self.getAllTeams();
					self.clearNewTeams();
				});
			} else {
				alert("Error:\nEine oder mehrere Mannschaften sind bereits vorhanden und können somit nicht noch einmal hinzugefügt werden!");
			}
		} else {
			alert ("Error:\nEs sind nicht alle erforderlichen Felder ausgefüllt!");
		}
	};
	// -----------------------------------------

	self.isUniqueTeam = function() {

		for(var i = 0; i < self.newTeams().length; i++){
			for(var j = 0; j < self.teams().length; j++){
				if((self.newTeams()[i].name().toLowerCase() == self.teams()[j].name().toLowerCase()) && (self.newTeams()[i].divisionId() == self.teams()[j].divisionId())){
					return false;
				}
			}
		}
		return true;
	};

	self.addTeamButtonLabel = ko.computed(function() {
		if(self.newTeams().length > 1) {
			return "Mannschaften hinzufügen";
		} else {
			return "Mannschaft hinzufügen";
		}
	});

	self.elementFadeIn = function(element) {
		$(element).hide().fadeIn();
	};

	self.elementFadeOut = function(element) {
		$(element).fadeOut();
	};

	// get all divisions from DB with Ajax
	self.getAllDivisionNames();

	// get all teams from DB with Ajax
	self.getAllTeams();
}

function Team(id, name, initialDivisionId){
	var self = this;
	self.id = id;
	self.name = ko.observable(name).extend({ required: true });
	self.divisionId = ko.observable(initialDivisionId).extend({ required: true });
	self.divisionName = ko.computed(function() {

		for(var i = 0; i < teamViewModel().divisions.length; i++){
			if(self.divisionId() == teamViewModel().divisions[i].id){
				return teamViewModel().divisions[i].name;
			}
		}
	});
}


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
		errorElementClass: "errorElement",
		messagesOnModified: false
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


function controlOkButton(){
	var n = $(".teamSelectCheckbox:checked").length;
	if(n === 0){
		$("#OK_Button").prop("disabled", true);
	} else {
		$("#OK_Button").prop("disabled", false);
	}
}