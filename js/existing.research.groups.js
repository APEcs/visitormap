
	var delete_target;
	

	function initializeGroups() 
	{	
		//if AJAX call is success, calls handleJson
		$.getJSON(SITE_URL + 'research_groups/data', handleJson);
	 }

	
	function handleJson(json)
	{	 
		$("#table").empty();	
		
		//go trhough the json data
		$.each(json, function()
		{
			row = "<tr>";
			row += "<td>" + this.acronym + "</td>" +
				"<td>" + this.name + "</td>" +
				"<td>" + this.unique_visitors + "</td>" +
				"<td>" + this.visits + "</td>";
			
			row += add_edit_group(this.id, this.visits);
			row += "</tr>";
			
			$("#table").append(row);	

		}); //end of each inst
		
		initTableSorter();
		
	 } //end of handleJson function

	
	function add_edit_group(id, visits)
	{
		ret = "<td><a class='btn btn-mini btn-info' type='button' id='editGroupBtn' href='"+id+"'><i class='icon-pencil'></i></a> ";
		
		if (visits===0)
			{
			ret += "<a class='btn btn-mini btn-danger' href='"+id+"' id='deleteGroupBtn' type='button' ><i class='icon-trash'></i></a>";
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
			sortDesc   : 'icon-chevron-down',

		});
		
		$("#tableToSort").tablesorter({
			theme : "bootstrap",
			widthFixed: true,
			headerTemplate : '{content} {icon}',
			widgets : [ "uitheme", "filter", "zebra" ],

			widgetOptions : {
				zebra : ["even", "odd"],
				filter_reset : ".reset",
			}
		})
		
		$("#tableToSort").trigger("update").trigger("appendCache");
	    
		var sorting = [[1,0]];
	    $("#table").trigger("sorton",[sorting]);
	}

	
    $("#table").on("click", "#editGroupBtn", function (e) {
    	e.preventDefault();
    	prepareEditGroup($(this).attr("href"));
    });
    
    
    function prepareEditGroup(group_id) {
    	var post_data = {
        		id: group_id,
        		csrf_token_name: $.cookie('csrf_cookie_name')
        	};
        	var posting = $.post( SITE_URL +'research_groups/get_edit_data', post_data, populateEditGroup);
    	return false;
    }
    
    function populateEditGroup(data){
    	
    	if(data) {
    		$("input[name=group_id]").val(data.id);
    		$("#group_acronym").val(data.acronym);
    		$("#group_name").val(data.name);
    		$('#new-research-group').modal('show')
    	}	
    }


	//delete group confirmation modal
    $("#table").on("click", "#deleteGroupBtn", function (e) {
    	e.preventDefault();
        openConfirmModal();
        delete_target = $(this).attr("href");
    });
    
    function openConfirmModal() {
    	
        $("#confirmDiv").confirmModal({
			heading:'Are you sure?',
			body:"Sure you want to delete that group?<br><br>",
            callback: function () {
            	doGroupDelete();
            }
        });
    }

	
    function doGroupDelete()
    {
		var post_data = {
				id: delete_target,
				csrf_token_name: $.cookie('csrf_cookie_name')
			};

			$.post( SITE_URL +'research_groups/delete_group', post_data, handleDeleteGroupResponse);		
    }


    function handleDeleteGroupResponse(data)
    {
    	if(data && data.success) {
    		initializeGroups();
    		if($(".group-deleted").is(":visible")) {
    			$(".group-deleted").slideUp();
    		}
    		$(".group-deleted").slideDown();
    	}
    }
    