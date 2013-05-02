function TeamViewModel(){

	var self = this;

	// TODO fill from DB
	self.divisions = [
			{ divNr: "", divName: "Spieklasse wählen"}, //das ist immer da
			{ divNr: 1, divName: "Herren LK1"},
			{ divNr: 2, divName: "Herren LK2"}
	];

	var teamCounter = 0;

	// TODO fill from DB
	self.teams = ko.observableArray([
		new Team(1, "KCW", self.divisions[1]),
		new Team(2, "WSF", self.divisions[1])
		//new Team("", self.divisions[0])
	]);


	self.addTeam = function(){
		self.teams.push(new Team(teamCounter, "", self.divisions[0]));
		teamCounter++;
	};

	self.removeTeam = function(team){
		self.teams.remove(team);
	};

	self.buttonLabel = ko.computed(function(){
		if(self.teams().length > 1){
			return "Mannschaften hinzufügen";
		} else {
			return "Mannschaft hinzufügen";
		}
	}, self);


	self.saveTeamsInDB = function(){

		if ( !$('#teamForm').valid() ){
			// $('#teamForm').showErrors();
			return false;
		}


		//var jsondata = ko.toJSON({teams: self.teams()});
		var jsonTeamData = ko.mapping.toJSON({teams : self.teams()});

		// TODO: beim Callback von $.post die neuen Daten aus der DB laden und anzeigen
		$.post("backend/handleAjaxRequests.php", {jsonTeamData: jsonTeamData}, function(data) { alert("Data: \n" + data);});
		// $.get("ajaxtest.php", {jsondata: jsondata}, function(data) { alert("Data: \n" + data); } );

	};

	// get JSON data from team.php and populate team array
	/*$.getJSON("team.php", function(allData){
		var mappedTeams = $.map(allData, function(item){ return new Team(item); });
		self.teams(mappedTeams);
	});*/
}

function Team(teamNr, name, division){
	var self = this;
	self.teamNr = teamNr;
	self.name = name;
	self.division = division;
}

function Player(number, firstname, name, captain, teamNr){
	var self = this;
	self.number = number;
	self.firstname = firstname;
	self.name = name;
	self.captain = captain;
	self.teamNr = teamNr;
}

// activate knockout.js in jquery document ready()
$(document).ready(function(){
	ko.applyBindings(new TeamViewModel());
});