	
	var map_points = [];
	var markers = [];
	var visits_array = [];
	var groups_array = [];
	var countries_array = [];
	var regions_array = [];
	var hosts_array = [];
	var openinfo;
	var iterator = 0;
	var map;
	var data_json;
	var minBound_date;
	var maxBound_date;
	var min_date;
	var max_date;
	var selected_group;
	var selected_host;
	var selected_region;
	var markerClusterer;

	var mapStyles = [
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "on" },
      { "color": "#ffffff" }
    ]
  },{
    "featureType": "administrative",
    "elementType": "geometry.fill",
    "stylers": [
      { "visibility": "simplified" }
    ]
  },{
    "featureType": "water",
    "stylers": [
      { "visibility": "simplified" }
    ]
  },{
    "featureType": "landscape",
    "stylers": [
      { "visibility": "on" }
    ]
  },{
    "featureType": "poi",
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "road.highway",
    "elementType": "labels",
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "landscape",
    "stylers": [
      { "gamma": 0.71 },
      { "saturation": -71 }
    ]
  },{
    "featureType": "road",
    "stylers": [
      { "weight": 0.5 }
    ]
  },{
    "featureType": "road.local",
    "stylers": [
      { "weight": 0.8 }
    ]
  },{
    "featureType": "road",
    "stylers": [
      { "hue": "#c300ff" },
      { "saturation": -50 }
    ]
  }
]

      var styles = [{
        url: SITE_URL+'img/group26-alt2.png',
        height: 27,
        width: 26,
        textColor: '#ffffff',
        textSize: 13
      }, {
        url: SITE_URL+'img/group36-alt2.png',
        height: 37,
        width: 36,
        textColor: '#ffffff',
        textSize: 14
      }, {
        url: SITE_URL+'img/group46-alt2.png',
        height: 47,
        width: 46,
        textColor: '#ffffff',
        textSize: 14
      }];

	function map_point(marker, infowindow, title, visible, instId) {
	    this.visitsArray = [];
		this.instId = instId;
	    this.marker = marker;
	    this.infowindow = infowindow;
	    this.title = title;
	    this.visible = visible;

	    
	    this.addVisit = function (newVisit) {
	        this.visitsArray.push(newVisit);
	        if(newVisit.visible) {
	        	this.marker.visible_visits++;
	        }
	    };
		
		this.updateVisible = function(){
		
			var visible_visits_count=0;	
			var bool = Boolean(0);
				
			$.each(this.visitsArray, function(key, value) {
				if(value.visible){
					visible_visits_count++;
					bool = Boolean(1);	
				}
			});
			if(bool) {this.updateInfoWindow()};
			this.marker.setVisible(bool);
			this.visible = bool;
			this.marker.visible_visits = visible_visits_count;
			this.marker.set('labelContent', this.marker.visible_visits);
		};
		
		
		
		this.updateInfoWindow = function(){
			var newtext = "";
			if($("#link-to-new-visit").length) {
				newtext += "<h4 class='google-infowindow'><a title='Edit' href='"+SITE_URL+"institutions/edit_institution?id="+instId+"&origin=home'>" + this.title + "</a></h4>";
			}
			else {
				newtext += "<h4 class='google-infowindow'>" + this.title + "</h4>";
			}
			newtext += "<table class='table table-condensed table-bordered table-infowindow'>"+
			"<thead>"+
				"<tr>"+
					"<th>Visitor</th>"+
					"<th>Dates</th>"+
					"<th>Group visited</th>"+
					"<th>Host</th>"+
				"</tr>"+
			"</thead>"+
			"<tbody>";
			
			$.each(this.visitsArray, function(key, value) {
				
				if(value.visible){
					newtext += "<tr>";
					newtext += "<td><div class='reportproblem-link' visit_id='"+value.id+"' title='Details'><b>" + this.visitor_first_name +" "+ this.visitor_last_name + "</b></div></td>";	
					
					newtext += 	"<td>" + this.from_date.format("yyyy-mm-dd") + " to " + this.to_date.format("yyyy-mm-dd") + "</td>" + 
								"<td>" + this.group + "</td>" + 
								"<td>" + this.host_first_name +" "+ this.host_last_name + "</td>" + 
					"</tr>";	
				}
			});
			newtext += "</tbody></table>";
			this.infowindow.setContent(newtext);
		};
		

	}

	function visit(id, visitor_title, visitor_first_name, visitor_last_name, 
			visitor_sex, from_date, to_date, host_title,
			host_first_name, host_last_name, position, group, institution, 
			institution_id, department, country, country_iso, region, visible, hide_name) {
	    this.id = id;
	    this.visitor_title =visitor_title;
		this.visitor_first_name = visitor_first_name;
		this.visitor_last_name = visitor_last_name;
		this.visitor_sex = visitor_sex;
	    this.from_date = from_date;
	    this.to_date = to_date;
	    this.host_title = host_title;
	    this.host_first_name = host_first_name;
	    this.host_last_name = host_last_name;
	    this.position = position;
	    this.group = group;
	    this.institution = institution;
	    this.institution_id = institution_id;
	    this.department = department;
	    this.country = country;
	    this.country_iso = country_iso;
	    this.region = region;
	    this.visible = visible;
	    this.hide_name = hide_name;
	}
	
	 //loads the googleMaps
	google.maps.event.addDomListener(window, 'load', initializeGoogle);

	function initializeGoogle() 
	{
		
		//if AJAX call to home/data is success, calls handleJson
		$.getJSON(SITE_URL + 'home/data', handleJson);

		var myLatlng = new google.maps.LatLng(53.4749899,-2.2354274);
		var mapOptions = 
		{
			zoom: 2,
			minZoom: 2,
			scrollwheel: false,
			center: new google.maps.LatLng(19.642588, 17.578125),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: true,
			mapTypeControl: false,
			panControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
			styles: mapStyles	
		};
		
		if ($("#map_canvas").length) {
		map = new google.maps.Map(document.getElementById('map_canvas'),
			 mapOptions);
		}	
			
		var home = new google.maps.Marker(
		{
			position: myLatlng,
			title:"University of Manchester",
			animation: google.maps.Animation.DROP,
			map: map,
			icon: SITE_URL + 'img/home.png',
			shadow: SITE_URL + 'img/shadow.png'
			//zIndex: -999
		});

		var homewindow = new google.maps.InfoWindow({
		 content: "The University of Manchester"
		});

		google.maps.event.addListener(home, 'click', function() {
			homewindow.open(map,home);
		});

		google.maps.event.addListener(map, 'click', function() {
			if(openinfo) { openinfo.close(); }
			openinfo = null;
		});

	 }

	

	function handleJson(json)
	{	 
		data_json = json;	
		
		//sort according to latest_date
		data_json.points.sort(function(obj1, obj2) {
			// Descending: second date less than the first
			return obj2.latest_date.localeCompare(obj1.latest_date);
		}); 
		var drry = data_json.points[0].latest_date.split("-");

		//set slider minimum value
		maxBound_date = new Date(parseInt(drry[0],10),parseInt(drry[1],10)-1,parseInt(drry[2],10));
		
		//set slider max value
		var drry2 = data_json.points[data_json.points.length-1].earliest_date.split("-");
		minBound_date = new Date(parseInt(drry2[0],10),parseInt(drry2[1],10)-1,parseInt(drry2[2],10));

		//set initial slider selection min and max
		//var today = new Date();
		//if(maxBound_date > today) { max_date = today; }
		//else { max_date = maxBound_date;	}
		max_date = maxBound_date;

		//min_date = new Date(minBound_date.getTime()+(31556926000*1));
		
		min_date = minBound_date;
			
		selected_group = "All Groups";
		selected_host = "All Hosts";
		selected_region = "All Regions";
		
		//All slider's default values are set, let's initiate it
		initiateSlider();

		//go trhough the json data about points	
		$.each(data_json.points, function()
		{

			var mapPoint = createMarker(new google.maps.LatLng(this.lat, this.long), 
					this.institution, iterator, this.institution_id);
			var visible = Boolean(0);
			var parent = this;
			
			//go through each visit of a point
			$.each(this.visits, function() 
			{
				visible = Boolean(0);
				var darray = this.from_date.split("-");
				var visit_from_date = new Date(parseInt(darray[0],10),
					parseInt(darray[1],10)-1,
					parseInt(darray[2],10));
				this.from_date = visit_from_date;

				var darray2 = this.to_date.split("-");
				var visit_to_date = new Date(parseInt(darray2[0],10),
					parseInt(darray2[1],10)-1,
					parseInt(darray2[2],10));
				this.to_date = visit_to_date;

				if(this.from_date>=min_date && this.from_date<=max_date) 
					{ 
					visible = Boolean(1);					
					}
				//create new visit object and add that to global array and to mapPoint.
				var vis = new visit(this.id, this.visitor_title, this.visitor_first_name, this.visitor_last_name, 
						this.visitor_sex, this.from_date, this.to_date, this.host_title, 
						this.host_first_name, this.host_last_name, this.position_name,
						this.group, parent.institution, parent.institution_id, this.department,
						parent.country, parent.country_iso, parent.region, visible, this.hide_name);
				mapPoint.addVisit(vis);
				visits_array.push(vis);
				
				//populate groups_array
				if($.inArray(this.group, groups_array) == -1) { groups_array.push(this.group); }
				
				//populate hosts_array
				var host_name = this.host_first_name + " " + this.host_last_name;
				if($.inArray(host_name, hosts_array) == -1) { hosts_array.push(host_name); }
			}); //end of each visit
			
			
			//populate regions_array
			if($.inArray(this.region, regions_array) == -1) { regions_array.push(this.region); }
			
			mapPoint.updateVisible();
			iterator++;
		}); //end of each point
		
		populateSelectors();
		populateTable();
		initTableSorter();
		
		
		markerClusterer = new MarkerClusterer(map, markers, {
          gridSize: 35,
          averageCenter: true,
          ignoreHidden: true,
          'calculator': clusterIconCalc,
          zoomOnClick: false,
          styles: styles
        });	

        
        google.maps.event.addListener(markerClusterer, "click", function(c) {
	    	var currentZoom = map.getZoom();
	    	if(openinfo) { openinfo.close(); }

	    	if(currentZoom < 18) {
	    		var newCenter = new google.maps.LatLng(c.getCenter().lat(), c.getCenter().lng());
				map.setCenter(newCenter);
				map.setZoom(currentZoom+1);
	    	}
		});
		
		
		
	 } //end of handleJson function


	
	//populate all selectors
	function populateSelectors()
	{
		$.each(groups_array, function() {
			$("#groupSelector").append("<option value='"+this+"'>"+this+"</option>");
		});
		
		hosts_array.sort();
		$.each(hosts_array, function() {
			$("#hostSelector").append("<option value='"+this+"'>"+this+"</option>");
		});
		
		$.each(regions_array, function() {
			$("#regionSelector").append("<option value='"+this+"'>"+this+"</option>");
		});
	}
	
	
	
	function populateTable()
	{
		$("#table").empty();
		
		$.each(visits_array, function() {
			if(this.visible)
				{
				var row = "<tr visitId='"+this.id+"'>";
				
				if(this.visitor_first_name == "Anonymous") 
				{
					row += "<td class='table-name-column'><div class='reportproblem-link' visit_id='"+this.id+"' title='Details'>" + this.visitor_first_name + "<div></td>";
				}
				else 
				{
					row += "<td  class='table-name-column'><div class='reportproblem-link' visit_id='"+this.id+"' title='Details'>" + this.visitor_first_name + " " +  this.visitor_last_name + "<div></td>";
				}
					
				row += "<td class='country-column'><img src='"+SITE_URL+"img/blank.gif' class='flag flag-"+this.country_iso+"' alt='"+this.country+"' />"+this.country+"</td>" +
					"<td class='inst-column' id='inst"+this.institution_id+"'>" + this.institution + "</td>" +
					"<td class='group-column'>" + this.group + "</td>" +
					"<td class='host-column'>" + this.host_first_name + " " +  this.host_last_name + "</td>" +
					"<td class='from-column'>" + this.from_date.format("yyyy-mm-dd") + "</td>" +
					"<td class='to-column'>" + this.to_date.format("yyyy-mm-dd") + "</td>";
				//row += add_edit_visit(this.id);
							
				row += "</tr>";
				
				$("#table").append(row);	
				}
		});
	}

	
	function add_edit_visit(id)
	{
		/*
		if ($("#edit-visit-table-head").length)
			{
			return "<td><a class='btn btn-mini' type='button' href="+SITE_URL+"visits/edit_visit?id="+id+">Edit</a> " +
					"<a class='btn btn-mini btn-danger' type='button' href="+SITE_URL+"delete_visit?id="+id+">Delete</a>" +
					"</td>";
			}
		*/	
	}
	
	function initTableSorter()
	{
		$.extend($.tablesorter.themes.bootstrap, {
			table      : 'table table-bordered',
			header     : 'bootstrap-header', // give the header a gradient background
			sortNone   : 'bootstrap-icon-unsorted',
			sortAsc    : 'icon-chevron-up',
			sortDesc   : 'icon-chevron-down'

		});
		
		$("#tableToSort").tablesorter({
			theme : "bootstrap",
			widthFixed: true,
			headerTemplate : '{content} {icon}',
			widgets : [ "uitheme", "filter", "zebra" ],

			widgetOptions : {
				zebra : ["even", "odd"],
			}
		})
		
		 $('#tableToSort').bind('filterEnd', function() {
			 updateVisibility(Boolean(1));
		 });
	}
	
	 // createMarker function
	 function createMarker(myLatLng, title, i, instId)
	 {
		var infowindow = new google.maps.InfoWindow({});		
		
		var marker_image = {
    		url: SITE_URL + 'img/pin5.png',
    		// This marker is 20 pixels wide by 32 pixels tall.
    		size: new google.maps.Size(24, 24),
    		// The origin for this image is 0,0.
    		origin: new google.maps.Point(0,0),
    		// The anchor for this image is the base of the flagpole at 0,32.
    		anchor: new google.maps.Point(0, 24)
  		};
		
		var marker = new MarkerWithLabel({
			position: myLatLng, 
			title:title,
			icon: marker_image,
			visible: Boolean(0),
			labelClass: "map-labels",
			labelAnchor: new google.maps.Point(-9, 25),
			labelInBackground: false
		});
		marker.visible_visits = 0;
		mp = new map_point(marker, infowindow, title, Boolean(0), instId);
		map_points[i] = mp;
		markers[i] = marker;
		
		google.maps.event.addListener(marker, 'click', function() 
		{
			infowindow.open(map,map_points[i].marker);
			
			if(openinfo) { openinfo.close(); }
			openinfo = infowindow;	
		});
		
		google.maps.event.addListener(infowindow, 'closeclick', function() 
		{
			openinfo = null;
		});
		
		return mp;
	 }
			


	//starts the slider
	function initiateSlider()
	{
		$("#slider").dateRangeSlider({
			bounds:{
				min: minBound_date,
				max: maxBound_date
			},
			defaultValues:{
				min: min_date,
				max: max_date
			} 
		});
	}
	
	
	function sliderChanged(event, data)
	{
		min_date = data.values.min;
		max_date = data.values.max;
		
		updateVisibility();
	}
	

	function groupSelected()
	{
		selected_group = $("#groupSelector").find(":selected").text();
		selected_host = "All Hosts";
		
		hosts_array.length = 0;
		
		$.each(visits_array, function() {
			host_full_name = this.host_first_name + " " + this.host_last_name;
			if((selected_group == "All Groups" || selected_group == this.group) && $.inArray(host_full_name, hosts_array) == -1)
				{
				hosts_array.push(host_full_name);
				}
		});
		
		hosts_array.sort();
		
		$("#hostSelector").empty();
		$("#hostSelector").append("<option value='All Hosts'>All Hosts</option>");
		
		$.each(hosts_array, function() {
			$("#hostSelector").append("<option value='"+this+"'>"+this+"</option>");
		});
		
		updateVisibility();
	}
	
	
	function hostSelected()
	{
		selected_host = $("#hostSelector").find(":selected").text();
		updateVisibility();
	}
	
	function regionSelected()
	{
		selected_region = $("#regionSelector").find(":selected").text();
		updateVisibility();
	}
	
	
	//calculates what number should be shown on cluster icon
	//it's the total number of visitors under that cluster
	function clusterIconCalc(markers, numStyles) {
	    var total = 0;
        for (var i = 0;i < markers.length; i++) {
          total = total + markers[i].visible_visits;
        }
		if(total<10) index=1;
		else if (total>=10 && total<50) index=2;
		else index=3;

        
        return {
          text: total,
          index: index
        };
      };
	
	
	function updateVisibility(filter)
	{
		if(openinfo) { openinfo.close(); }
		openinfo = null;

		$.each(visits_array, function() {
			this.visible = Boolean(0);
			});
		
		$.each(visits_array, function() {
			
			if(this.from_date>=min_date && this.from_date<=max_date) 
				{ 	
				if (selected_region == "All Regions" || this.region == selected_region)
					{
					if (selected_group == "All Groups" || this.group == selected_group)
						{
						if (selected_host == "All Hosts" || (this.host_first_name +" "+ this.host_last_name) == selected_host)
							{
							
							if(filter)
								{
								tableFilterIfVisitVisible(this);
								}
							else
								{
								this.visible = Boolean(1);
								}
							}
						}
					}
				}
		});
		
		$.each(map_points, function(){
			this.updateVisible();
		});
		
		//do not repopulate the table if visibility update is coming from table filters
		if(!filter){
			populateTable();
			$("#tableToSort").trigger("update").trigger("appendCache");
		}
		markerClusterer.repaint();
	}
	
	
	function tableFilterIfVisitVisible(visit) {
		if(!$("[visitid='"+visit.id+"']").hasClass("filtered"))
			{
			visit.visible = Boolean(1);
			}
	}
	
	
	//adds an event listener to the slider
	$("#slider").on("userValuesChanged", sliderChanged);
	
	//adds tooltip
	$('#edit-inst-tooltip').tooltip();
    
    
    function prepareDataAndShowModal(element) {
    	var index=0;
    	var clickedVisit = element.attr('visit_id');
    	$.each(visits_array, function() {
    		if(this.id === clickedVisit) {
    			return false;
    		}
    		index++;
    	});
    	populateReportProblem(visits_array[index]); //function in report.problem.js    
    }
    
    $(document).ready(function(){
    
    	if($.getUrlVar('inst_edit_success')==1 || $.getUrlVar('visit_edit_success')==1) {
			$("#institution_message").append("<span class='label label-success on-the-map'>Success! Changes were saved</span>").show().delay(4000).fadeOut(1000);
		}

		$("#map_canvas").on("click", ".reportproblem-link", function (e) {
    		e.preventDefault();
    		var element = $(this);
    		prepareDataAndShowModal(element);
    	});
    
   		$("#table").on("click", ".reportproblem-link", function (e) {
    		e.preventDefault();
    		var element = $(this);
    		prepareDataAndShowModal(element);
    	});   
    	
   		$("#mapButton").on("click", function (e) {
   			e.preventDefault();
    		$("#mapCheckbox").prop("checked", !$("#mapCheckbox").prop("checked"));
    		$(".home-map").toggle(); 
    	});      	 		

   		$("#tableButton").on("click", function (e) {
   			e.preventDefault();
   			$("#tableCheckbox").prop("checked", !$("#tableCheckbox").prop("checked"));
    		$("#mainTable").toggle(); 
    	}); 		

		$("#mapCheckbox").on("click", function (e) {
			$(".home-map").toggle();
		});

		$("#tableCheckbox").on("click", function (e) {
			$("#mainTable").toggle();
		});

		$("#statsCheckbox").on("click", function (e) {
			e.preventDefault();
			this.checked = false;
			location.href = SITE_URL + "stats";
		});		
		
    });
    
	