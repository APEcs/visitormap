// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback($.getJSON(SITE_URL + 'stats/data', handleJson));

function bySortedValue(a,b,c){var d=[];for(var e in a)d.push([e,a[e]]);d.sort(function(a,b){return a[1]>b[1]?1:a[1]<b[1]?-1:0});for(var f=d.length;f--;)b.call(c,d[f][0],d[f][1])}

//Load the data

var regions_array = [];
var groups_array = [];

var selected_group = "All Groups";
var selected_region = "All Regions";

var visits;
var yearlyData = {};
var regionData = {};
var countryData = {};
var groupData = {};

var totalNo; //total number of visits;
var totalNoOfYears;
var totalNoOfRegions;
var totalNoOfCountries;
var totalNoOfGroups;

var generalOptions

var lineChart;
var lineData;
var lineOptions;

var regionsChart;
var regionsData
var regionsOptions;

var countriesChart;
var countriesData
var countriesOptions;

var groupsChart;
var groupsData
var groupsOptions;

var visitsHistogramChart;
var visitsHistogramData;
var visitsHistogramOptions;

var allLoaded = false;

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.

function drawChart() {
	
	
	generalOptions = {
		'fontName': "'Open Sans', Arial, sans-serif",
		'fontSize': 13,
		'titleTextStyle':  {color: "#333333", fontName: "'Open Sans', Arial, sans-serif", fontSize: 18},
		'pointSize': 5,
		}; 
	// Create the data table.
	lineData = google.visualization.arrayToDataTable(populateLineChart());

	//Regions pie data
	regionsData = google.visualization.arrayToDataTable(populateRegionChart());

	//Countries chart data
	countriesData = google.visualization.arrayToDataTable(populateCountryChart());

	//Groups pie data
	groupsData = google.visualization.arrayToDataTable(populateGroupChart());	
	
	//Visits histogram data
	visitsHistogramData = google.visualization.arrayToDataTable([
		['Visits per year', 'Frequency'],
		[3, 1],
		[4, 1],
		[5, 2],
		[6, 0],
		[7, 1],
		[8, 4],
		[9, 3],
		[10, 4],
		[11, 4],
		[12, 5],
		[13, 6],
		[14, 3],
		[15, 2],
		[16, 2],
		[17, 1]
	]);	
	
	// Set charts options
	lineOptions = {'title':'Visits per year and their average length',
		'animation': {duration: 800, easing: 'out'},
		'colors': ['#660099', '#e0e0d4'],
		'width':"100%",
		'height': 300,
		'chartArea': {left: 35, top: 68,  width: "86%"},
		'seriesType': "line",
		'series': {1: {type: "bars", targetAxisIndex: 1}},
		vAxes:[
				{title:'No of visits', textStyle:{color: '#660099'}, titleTextStyle:{color: '#660099'}, minValue: 0, maxValue: 28}, // Axis 0
				{title:'Avg visit length in days',textStyle:{color: '#b2b0a5'}, titleTextStyle:{color: '#b2b0a5'}, minValue: 0, maxValue: 1095} // Axis 1
			]
		};
	 $.extend(true, lineOptions, generalOptions);


	regionsOptions = {'title':'Regional distribution',
		'colors': ['#454a44', '#a5b1a3', '#6a7269', '#c5d3c2'], 
		'width':"100%",
		'height': 300,
		'chartArea': {left: 10, top: 50, width: "100%"}
		};		
	$.extend(true, regionsOptions, generalOptions);


	countriesOptions = {'title':'Countries distribution',
		'animation': {duration: 800, easing: 'out'},
		'colors': ['#b53e76'],
		'width':"100%",
		'height': 900,
		'chartArea': {left: 125, top: 50, width: "100%", height: 770},
		'legend': {position: 'none'},
		hAxis:{title:'No of visits', titleTextStyle:{color: '#b53e76'}}		
		};		
	$.extend(true, countriesOptions, generalOptions);

	groupsOptions = {'title':'Research Groups distribution',
		'animation': {duration: 800, easing: 'out'},
		'colors': ['#660099'],
		'width':"100%",
		'height': 610,
		'chartArea': {left: 255, top: 50, width: "100%", height: 480},
		'legend': {position: 'none'},
		hAxis:{title:'No of visits', titleTextStyle:{color: '#660099'}}		
		};		
	$.extend(true, groupsOptions, generalOptions);

	visitsHistogramOptions = {'title':'Visits per year histogram',
		'width':"100%",
		'height': 300,
		'chartArea': {left: 40, top: 50, width: "100%"},
		'legend': {position: 'none'},
		hAxis:{title:'No of visits'},
		vAxis:{title:'Frequency'}		
		};		
	$.extend(true, visitsHistogramOptions, generalOptions);

	//initiate and draw
	lineChart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
	lineChart.draw(lineData, lineOptions);
	
	regionsChart = new google.visualization.PieChart(document.getElementById('region_pie_div'));
    regionsChart.draw(regionsData, regionsOptions);
    
    countriesChart = new google.visualization.BarChart(document.getElementById('countries_pie_div'));
    countriesChart.draw(countriesData, countriesOptions);
    
    groupsChart = new google.visualization.BarChart(document.getElementById('groups_pie_div'));
    groupsChart.draw(groupsData, groupsOptions);    
    
    //not implemented atm
    //visitsHistogramChart = new google.visualization.ColumnChart(document.getElementById('visits_histogram_div'));
    //visitsHistogramChart.draw(visitsHistogramData, visitsHistogramOptions); 
    allLoaded = true;   
}

// debulked onresize handler
function on_resize(c,t){onresize=function(){clearTimeout(t);t=setTimeout(c,100)};return c};

on_resize(function() {
	if(allLoaded){
		lineChart.draw(lineData, lineOptions);
		regionsChart.draw(regionsData, regionsOptions);
		countriesChart.draw(countriesData, countriesOptions);
		groupsChart.draw(groupsData, groupsOptions);
	}
	//visitsHistogramChart.draw(visitsHistogramData, visitsHistogramOptions);  
});


$(document).ready(function(){

	//$('#visits-per-year-span').popover({placement: 'top'});
	
	/*
	$("#visits-per-year-span").hover(function() {
    	//stuff to do on mouseenter
    	$('#visits-per-year-span').popover('show');
	}
	, function()
	{	
    	//stuff to do on mouseleave
    	$('#visits-per-year-span').popover('hide');
	});	
	
	$('#visits-histogram-modal').on('shown', function () {
		visitsHistogramChart.draw(visitsHistogramData, visitsHistogramOptions); 
	}); */
	
	$("#mapCheckbox, #tableCheckbox, #statsCheckbox").on("click", function (e) {
		this.checked = false;
		location.href = SITE_URL;
	});
	
});



function handleJson(json) {	

	//Save visits to local variable
	visits = json;		
	//Set the total visit no
	totalNo = json.length;
	populateStatistics(true);
	populateSelectors();
	drawChart();

} //end of handleJson


function populateStatistics(firstRun) {
	
	var one_day=1000*60*60*24;
	totalNoOfRegions = 0;
	totalNoOfCountries = 0;
	totalNoOfGroups = 0;
	$("#totalNo").text(totalNo);
	 
	//iterate over visits array.
	$.each(visits, function() {
	
		//if looping through visits after filter event then only include ones that are visible
		if(!firstRun && !this.visible) {
			return true;
		}
		
		var darray = this.from_date.split("-");
		var year = parseInt(darray[0], 10);
		
		var visit_from_date = new Date(parseInt(darray[0],10),
			parseInt(darray[1],10)-1,
			parseInt(darray[2],10));
		
		var darray = this.to_date.split("-");
		var visit_to_date = new Date(parseInt(darray[0],10),
			parseInt(darray[1],10)-1,
			parseInt(darray[2],10));		
		
		var visitLen = Math.ceil((visit_to_date.getTime()-visit_from_date.getTime())/(one_day))
		
		if(regionData[this.region] == undefined) {
			regionData[this.region] = 1;
			totalNoOfRegions++;
		}
		else {
			regionData[this.region]++;
		}
		if($.inArray(this.region, regions_array) == -1) { regions_array.push(this.region); }

		if(countryData[this.country] == undefined) {
			countryData[this.country] = 1;
			totalNoOfCountries++;
		}
		else {
			countryData[this.country]++;
		}
		
		if(groupData[this.group] == undefined) {
			groupData[this.group] = 1;
			totalNoOfGroups++;
		}
		else {
			groupData[this.group]++;
		}
		if($.inArray(this.group, groups_array) == -1) { groups_array.push(this.group); }		
		
		if(yearlyData[year] == undefined) {
			yearlyData[year] = {};
			yearlyData[year]['visits'] = 1;
			yearlyData[year]['totVisitsLen'] = visitLen;
		}
		else {
			yearlyData[year]['visits']++;
			yearlyData[year]['totVisitsLen'] += visitLen;
		}
		
	}); //end of each visit
	
	//calculate yearly average and avg visit length
	var yearAvg = 0;
	var lenAvg = 0;
	var total = 0;
	var totalLen = 0;
	totalNoOfYears = 0;
	$.each(yearlyData, function(index) {
		total += this.visits;
		totalLen += this.totVisitsLen;
		totalNoOfYears++;
	});
	yearAvg = Math.round((total/totalNoOfYears)*100)/100;
	
	lenAvg = Math.round((totalLen/totalNo)) ;
	
	$("#yearAvg").text(yearAvg);
	$("#lenAvg").text(lenAvg+" days");
	
}


function populateLineChart() {
	var output = new Array(totalNoOfYears+1);
	output[0] = ['Year', 'Visits', 'Avg visit length in days'];
	
	var i=1;
	$.each(yearlyData, function(index) {
		output[i] = [index, this.visits, Math.round((this.totVisitsLen/this.visits))];
		i++;
	});	
	
	return output;
}

function populateRegionChart() {
	var output = new Array(totalNoOfRegions+1);
	output[0] = ['Region', 'Visits per region'];

	var i=1;	
	bySortedValue(regionData, function(key, value) {
		output[i] = [value+" "+key, parseInt(value)];
		i++;    		
	});
	
	return output;
}

function populateCountryChart() {
	var output = new Array(totalNoOfCountries+1);
	output[0] = ['Country', 'Visits'];

	var i=1;	
	bySortedValue(countryData, function(key, value) {
		output[i] = [key+" "+value, parseInt(value)];
		i++;    		
	});
	
	return output;
}

function populateGroupChart() {
	var output = new Array(totalNoOfGroups+1);
	output[0] = ['Group', 'Visits'];

	var i=1;	
	bySortedValue(groupData, function(key, value) {
		var raw = key.split(" ");
		if(raw[raw.length-1] == "Group") {
			var lastIndex = key.lastIndexOf(" ")
			var key = key.substring(0, lastIndex);
		}
		output[i] = [key+" "+value, parseInt(value)];
		i++;    		
	});
	
	return output;
}

//populate all selectors
function populateSelectors() {
	groups_array.sort();
	$.each(groups_array, function() {
		$("#groupSelector").append("<option value='"+this+"'>"+this+"</option>");
	});	
	
	$.each(regions_array, function() {
		$("#regionSelector").append("<option value='"+this+"'>"+this+"</option>");
	});
}

function groupSelected() {
	selected_group = $("#groupSelector").find(":selected").text();
	updateVisibility();
}

function regionSelected() {
	selected_region = $("#regionSelector").find(":selected").text();
	updateVisibility();
}

function updateVisibility() {
	totalNo=0;
	$.each(visits, function() {
		this.visible = Boolean(0);
		});
	
	$.each(visits, function() {
	
		if (selected_region == "All Regions" || this.region == selected_region)
			{
			if (selected_group == "All Groups" || this.group == selected_group)
				{	
				this.visible = Boolean(1);
				totalNo++;
				}
			}
	});
	cleanUpRawStatistics();
	
	if(totalNo>0) { 
		//at least one visit is visible
		$("#line_chart_div").show();
		$("#region_pie_div").show();
		$("#countries_pie_div").show();
		$("#groups_pie_div").show();
		populateStatistics(false); //not first run
	
		lineData = google.visualization.arrayToDataTable(populateLineChart());
		regionsData = google.visualization.arrayToDataTable(populateRegionChart());
		countriesData = google.visualization.arrayToDataTable(populateCountryChart());
		groupsData = google.visualization.arrayToDataTable(populateGroupChart());	
	
		countriesOptions.height = (300+(totalNoOfCountries*15));
		countriesOptions.chartArea = {left: 125, top: 50, width: "100%", height: (150+(totalNoOfCountries*15))};
	
		groupsOptions.height = (250+(totalNoOfGroups*15));
		groupsOptions.chartArea = {left: 255, top: 50, width: "100%", height: (150+(totalNoOfGroups*15))};
			
		lineChart.draw(lineData, lineOptions);
		regionsChart.draw(regionsData, regionsOptions);
		countriesChart.draw(countriesData, countriesOptions);
		groupsChart.draw(groupsData, groupsOptions);
		visitsHistogramChart.draw(visitsHistogramData, visitsHistogramOptions);
	} 
	else {
		//no visits are visible
		$("#totalNo").text(totalNo);
		$("#yearAvg").text(0);
		$("#lenAvg").text("0 days");
		$("#line_chart_div").hide();
		$("#region_pie_div").hide();
		$("#countries_pie_div").hide();
		$("#groups_pie_div").hide();
	} 	
}


function cleanUpRawStatistics() {
	yearlyData = {};
	regionData = {};
	countryData = {};
	groupData = {};

	totalNoOfYears = 0;
	totalNoOfRegions = 0;
	totalNoOfCountries = 0;
	totalNoOfGroups = 0;
}
