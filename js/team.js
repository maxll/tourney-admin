
// need for validation
var teamViewModel;

function TeamViewModel(){

	var self = this;

	// TODO fill from DB
	self.divisions = [
			{ divNr: 1, divName: "Herren LK1"},
			{ divNr: 2, divName: "Herren LK2"}
	];

	// TODO fill from DB
	self.teams = ko.observableArray([
		new Team("KCW", self.divisions[0]),
		new Team("WSF", self.divisions[0])
	]);//.extend({ validArray: true});

	// validation rule
	self.teams.extend({ validArray: true});

	self.addTeam = function(){
		self.teams.push(new Team("", ""));
	};

	self.removeTeam = function(team){
		self.teams.remove(team);
	};

	self.test = ko.observable(5).extend({ mustEqual: 5 });

	self.buttonLabel = ko.computed(function(){
		if(self.teams().length > 1){
			return "Mannschaften hinzufügen";
		} else {
			return "Mannschaft hinzufügen";
		}
	});

	self.saveTeamsInDB = function(){

		// got valid form?
		if(teamViewModel.isValid()){

			// var jsonTeamData = ko.toJSON({teams: self.teams()});
			var jsonTeamData = ko.mapping.toJSON({teams : self.teams()});

			// TODO: beim Callback von $.post die neuen Daten aus der DB laden und anzeigen
			$.post("backend/handleAjaxRequests.php", {jsonTeamData: jsonTeamData}, function(data) { alert("Data: \n" + data);});
			// $.get("backend/handleAjaxRequests.php", {jsondata: jsonTeamData}, function(data) { alert("Data: \n" + data); } );

		} else {
			alert ("ERROR:\nFehlerhafte oder Unvollständige Eingaben!\nZum Speichern, bitte beheben!");
		}
	};

	// get JSON data from team.php and populate team array
	/*$.getJSON("team.php", function(allData){
		var mappedTeams = $.map(allData, function(item){ return new Team(item); });
		self.teams(mappedTeams);
	});*/
}

function Team(name, initialDivision){
	var self = this;
	self.name = ko.observable(name).extend({ required: true });
	self.division = ko.observable(initialDivision).extend({ required: true });
}

function Player(number, firstname, name, captain, teamNr){
	var self = this;
	self.number = number;
	self.firstname = firstname;
	self.name = name;
	self.captain = captain;
	self.teamNr = teamNr;
}



function setupKnockoutValidation(){

	// deactivate error-text-messages, use ".errorElement" for element-decoration
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

	setupKnockoutValidation();

	teamViewModel = ko.validatedObservable(new TeamViewModel());
	ko.applyBindings(teamViewModel);

});