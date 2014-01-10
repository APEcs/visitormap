	
	var visits_array = [];
	var groups_array = [];
	var countries_array = [];
	var regions_array = [];
	var hosts_array = [];
	var openinfo;
	var iterator = 0;
	var minBound_date;
	var maxBound_date;
	var min_date;
	var max_date;
	var selected_group;
	var selected_host;
	var selected_region;
	var delete_target;



	function visit(id, visitor_first_name, visitor_last_name, from_date, 
			to_date, host_first_name, host_last_name, group, institution, 
			institution_id, country, country_iso, region, visible, hide_name) {
	    this.id = id;
		this.visitor_first_name = visitor_first_name;
		this.visitor_last_name = visitor_last_name;
	    this.from_date = from_date;
	    this.to_date = to_date;
	    this.host_first_name = host_first_name;
	    this.host_last_name = host_last_name;
	    this.group = group;
	    this.institution = institution;
	    this.institution_id = institution_id;
	    this.country = country;
	    this.country_iso = country_iso;
	    this.region = region;
	    this.visible = visible;
	    this.hide_name = hide_name;
	}
	
	

	function initializeVisits() 
	{
		visits_array.length = 0;
		groups_array.length = 0;
		countries_array.length = 0;
		regions_array.length = 0;
		hosts_array = [];		
		selected_group = "All Groups";
		selected_host = "All Hosts";
		selected_region = "All Regions";		
		
		//if AJAX call to home/data is success, calls handleJson
		$.getJSON(SITE_URL + 'home/data', handleJson);

	 }

	

	function handleJson(json)
	{	 
		//sort according to latest_date
		json.points.sort(function(obj1, obj2) {
			// Descending: second date less than the first
			return obj2.latest_date.localeCompare(obj1.latest_date);
		}); 
		var drry = json.points[0].latest_date.split("-");

		//set slider minimum value
		maxBound_date = new Date(parseInt(drry[0],10),parseInt(drry[1],10)-1,parseInt(drry[2],10));
		
		
		//sort according to earliest_date
		json.points.sort(function(obj1, obj2) {
			// Ascending: first date less than the previous
			return obj1.earliest_date.localeCompare(obj2.earliest_date);
		});
		var drry2 = json.points[0].earliest_date.split("-");

		//set slider max value
		minBound_date = new Date(parseInt(drry2[0],10),parseInt(drry2[1],10)-1,parseInt(drry2[2],10));
		
		max_date = maxBound_date;

		min_date = minBound_date;
		
		
		//go trhough the json data about points	
		$.each(json.points, function()
		{
			
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
				//create new visit object and add that to global array
				var vis = new visit(this.id, this.visitor_first_name, this.visitor_last_name, 
						this.from_date, this.to_date, this.host_first_name,  this.host_last_name,
						this.group, parent.institution, parent.institution_id,
						parent.country, parent.country_iso, parent.region, visible, this.hide_name);

				visits_array.push(vis);
				
				//populate groups_array
				if($.inArray(this.group, groups_array) == -1) { groups_array.push(this.group); }
				
				//populate hosts_array
				var host_name = this.host_first_name + " " + this.host_last_name;
				if($.inArray(host_name, hosts_array) == -1) { hosts_array.push(host_name); }
				
			}); //end of each visit
			
			
			//populate regions_array
			if($.inArray(this.region, regions_array) == -1) { regions_array.push(this.region); }

			iterator++;
		}); //end of each point
		
		populateSelectors();
		populateTable();
		initTableSorter();
		
	 } //end of handleJson function


	
	//populate all selectors
	function populateSelectors()
	{
		groups_array.sort();
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
				row = "<tr>";
				
				if(this.visitor_first_name == "Anonymous") 
				{
					row += "<td>" + this.visitor_first_name + "</td>";
				}
				else 
				{
					row += "<td>" + this.visitor_first_name + " " +  this.visitor_last_name + "</td>";
				}
					
				row += "<td><img src='"+SITE_URL+"img/blank.gif' class='flag flag-"+this.country_iso+"' alt='"+this.country+"' />" + this.country + "</td>" +
					"<td id='inst"+this.institution_id+"'>" + this.institution + "</td>" +
					"<td>" + this.group + "</td>" +
					"<td>" + this.host_first_name + " " +  this.host_last_name + "</td>" +
					"<td>" + this.from_date.format("yyyy-mm-dd") + "</td>" +
					"<td>" + this.to_date.format("yyyy-mm-dd") + "</td>";
				row += add_edit_visit(this.id);
				"</tr>";
				
				$("#table").append(row);	
				}
		});
	}

	
	function add_edit_visit(id)
	{
		if ($("#edit-visit-table-head").length)
			{
			return "<td><a class='btn btn-mini btn-info' type='button' href='"+SITE_URL+"visits/edit_visit?id="+id+"'><i class='icon-pencil'></i></a> " +
					"<a class='btn btn-mini btn-danger' href='"+id+"' id='deleteVisitBtn' type='button' ><i class='icon-trash'></i></a>" +
					"</td>";
			}
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
				filter_reset : ".reset"
			}
		})
		
		 $('#tableToSort').bind('filterEnd', function() {
			 updateVisibility(Boolean(1));
		 });
	}
	

	function groupSelected()
	{
		selected_group = $("#groupSelector").find(":selected").text();
		selected_host = "All Hosts";
		
		hosts_array.length = 0;
		
		$.each(visits_array, function() {
			if((selected_group == "All Groups" || selected_group == this.group) && $.inArray(this.host, hosts_array) == -1)
				{
				hosts_array.push(this.host);
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
	
	
	function updateVisibility(filter)
	{
		if(openinfo) { openinfo.close(); }
		openinfo = null;

		
		$.each(visits_array, function() {
			
			this.visible = Boolean(0);
			
			if(this.from_date>=min_date && this.from_date<=max_date) 
				{ 	
				if (selected_region == "All Regions" || this.region == selected_region)
					{
					if (selected_group == "All Groups" || this.group == selected_group)
						{
						if (selected_host == "All Hosts" || this.host == selected_host)
							{
							
							if(filter && $("#inst"+this.institution_id+":visible").text() == this.institution)
								{
								this.visible = Boolean(1);
								}
							if(!filter)
								{
								this.visible = Boolean(1);
								}
							}
						}
					}
				}
		});

		
		//do not repopulate the table if visibility update is coming from table filters
		if(!filter){
			populateTable();
			$("#tableToSort").trigger("update").trigger("appendCache");
		}
	}


	//delete visit confirmation modal
    $("#table").on("click", "#deleteVisitBtn", function (e) {
    	e.preventDefault();
        openConfirmModal();
        delete_target = $(this).attr("href");
    });
    
    function openConfirmModal() {
    	
        $("#confirmDiv").confirmModal({
			heading:'Are you sure?',
			body:"Sure you want to delete? <br>If it is person's only visit, his/her name will be deleted as well.<br><br>",
            callback: function () {
            	doVisitDelete();
            }
        });
    }

	
    function doVisitDelete()
    {
		var post_data = {
				id: delete_target,
				csrf_token_name: $.cookie('csrf_cookie_name')
			};

			$.post( SITE_URL +'visits/delete_visit', post_data, handleDeleteVisitResponse);		
    }


    function handleDeleteVisitResponse(data)
    {
    	console.log(data);
    	if(data && data.success) {
    		initializeVisits();
    		$("#delete-success").slideDown();
    	}
    }
    