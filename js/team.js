function TeamViewModel(){

	var self = this;

	// TODO fill from DB
	self.divisions = [
			{ divName: "Spieklasse wählen"},
			{ divName: "Herren LK1"},
			{ divName: "Herren LK2"}
	];

	self.teams = ko.observableArray([
		new Team("KCW", self.divisions[1]),
		new Team("WSF", self.divisions[1])
		//new Team("", self.divisions[0])
	]);

	self.addTeam = function(){
		self.teams.push(new Team("", self.divisions[0]));
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

	var testData = {
		"names": [
			{"name" : "name 1", "lastname" : "lastname 1"},
			{"name" : "name 2", "lastname" : "lastname 2"}
		]
	};

	self.saveTeamsInDB = function(){
		var dataj = "";
		// dataj = ko.toJSON(testData);
		// dataj = JSON.stringify(testData);
		dataj = "{\"name\": \"test\"}";

		// dataj = ko.utils.stringifyJson({teams : self.teams()});
		// dataj = ko.toJSON({teams: self.teams()});
		alert(dataj);

		$.ajax({
			type: "GET",
			// type: "POST",
			url: "ajaxtest.php",
			data: dataj,
			contentType: "application/json",
			success: function(data) { alert(data); },
			error: function(xhr, status, error) { alert("xhr: " + xhr + "\nstatus: " + status + "\nerror: " + error); }
		});

		// $.post("ajaxtest.php", dataj, function(data) { alert("Data Loaded: \n" + data);});
		// $.get("ajaxtest.php", data, function(data) { alert("Data Loaded: \n" + data);} );

	};

	// get JSON data from team.php and populate team array
	/*$.getJSON("team.php", function(allData){
		var mappedTeams = $.map(allData, function(item){ return new Team(item); });
		self.teams(mappedTeams);
	});*/
}

function Team(name, division){
	var self = this;
	self.name = name;
	self.division = division;
}

// activate knockout.js in jquery document ready()
$(document).ready(function(){
	ko.applyBindings(new TeamViewModel());
});