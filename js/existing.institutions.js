
	var delete_target;
	

	function initializeInstitutions() 
	{	
		if($.getUrlVar('edit_ok')==1) {
			$(".inst-updated").slideDown();
		}
		
		//if AJAX call to home/data is success, calls handleJson
		$.getJSON(SITE_URL + 'institutions/data', handleJson);

	 }

	

	function handleJson(json)
	{	 
		$("#table").empty();
		
		
		//go trhough the json data
		$.each(json, function()
		{
			row = "<tr>";
			row += "<td>" + this.name + "</td>";
				
			row += "<td>" + make_address_look_nice(this.address1, this.address2) + "</td>" +
				"<td>" + this.postal_code + "</td>" +
				"<td>" + this.city + "</td>" +
				"<td><img src='"+SITE_URL+"img/blank.gif' class='flag flag-"+this.alpha_2+"' alt='"+this.country+"' />" + this.country + "</td>" +
				"<td>" + this.no_of_departments + "</td>" +
				"<td>" + this.no_of_visits + "</td>";
			row += add_edit_inst(this.id, this.no_of_visits);
			"</tr>";
			
			$("#table").append(row);	

		}); //end of each inst
		
		initTableSorter();
		
	 } //end of handleJson function

	
	function make_address_look_nice(address1, address2) {
		ret = "";
		if(address1) ret += address1;
		if(address1 && address2) ret += ", ";
		if(address2) ret += address2;
	
		return ret;
	}
	
	
	function add_edit_inst(id, visits) {
		ret = "<td><a class='btn btn-mini btn-info' type='button' href='"+SITE_URL+"institutions/edit_institution?id="+id+"'><i class='icon-pencil'></i></a> ";
		
		if (visits===0)
			{
			ret += "<a class='btn btn-mini btn-danger' href='"+id+"' id='deleteInstBtn' type='button' ><i class='icon-trash'></i></a>";
			}
		ret += "</td>";
		return ret;
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
		
		$("#tableToSort").trigger("update").trigger("appendCache");
	    
		var sorting = [[0,0]];
	    // sort on the first column and third columns
	    $("#table").trigger("sorton",[sorting]);
	}



	//delete inst confirmation modal
    $("#table").on("click", "#deleteInstBtn", function (e) {
    	e.preventDefault();
        openConfirmModal();
        delete_target = $(this).attr("href");
    });
    
    function openConfirmModal() {
    	
        $("#confirmDiv").confirmModal({
			heading:'Are you sure?',
			body:"Sure you want to delete?<br> All institution's departments will be deleted as well.<br><br>",
            callback: function () {
            	doInstDelete();
            }
        });
    }

	
    function doInstDelete()
    {
		var post_data = {
				id: delete_target,
				csrf_token_name: $.cookie('csrf_cookie_name')
			};

			$.post( SITE_URL +'institutions/delete_institution', post_data, handleDeleteInstResponse);		
    }


    function handleDeleteInstResponse(data)
    {
    	console.log(data);
    	if(data && data.success) {
    		initializeInstitutions();
    		if($(".inst-deleted").is(":visible")) {
    			$(".inst-deleted").slideUp();
    		}
    		$(".inst-deleted").slideDown();
    	}
    }
    