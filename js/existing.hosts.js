
	var delete_target;
	

	function initializeHosts() 
	{	
		//if AJAX call is success, calls handleJson
		$.getJSON(SITE_URL + 'hosts/data', handleJson);
	 }

	
	function handleJson(json)
	{	 
		$("#table").empty();	
		
		//go trhough the json data
		$.each(json, function()
		{
			row = "<tr>";
			row += "<td>" + this.title + "</td>" +
				"<td>" + this.first_name + "</td>" +
				"<td>" + this.last_name + "</td>" +
				"<td>" + this.sex + "</td>" +
				"<td>" + this.unique_visitors + "</td>" +
				"<td>" + this.visits + "</td>";
			
			row += add_edit_inst(this.id, this.visits);
			row += "</tr>";
			
			$("#table").append(row);	

		}); //end of each inst
		
		initTableSorter();
		
	 } //end of handleJson function

	
	function add_edit_inst(id, visits)
	{
		ret = "<td><a class='btn btn-mini btn-info' type='button' id='editHostBtn' href='"+id+"'><i class='icon-pencil'></i></a> ";
		
		if (visits===0)
			{
			ret += "<a class='btn btn-mini btn-danger' href='"+id+"' id='deleteHostBtn' type='button' ><i class='icon-trash'></i></a>";
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
	    
		var sorting = [[1,0]];
	    $("#table").trigger("sorton",[sorting]);
	}

	
    $("#table").on("click", "#editHostBtn", function (e) {
    	e.preventDefault();
    	prepareEditHost($(this).attr("href"));
    });
    
    
    function prepareEditHost(host_id) {
    	var post_data = {
        		id: host_id,
        		csrf_token_name: $.cookie('csrf_cookie_name')
        	};
        	var posting = $.post( SITE_URL +'hosts/get_edit_data', post_data, populateEditHost);
    }
    
    function populateEditHost(data){
    	
    	if(data) {
    		$("input[name=host_id]").val(data.id);
    		$("#host_title").val(data.title);
    		$("#host_first_name").val(data.first_name);
    		$("#host_last_name").val(data.last_name);
    		$('#host_'+data.sex).click();
    		$('#new-host').modal('show');
    	}	
    }


	//delete host confirmation modal
    $("#table").on("click", "#deleteHostBtn", function (e) {
    	e.preventDefault();
        openConfirmModal();
        delete_target = $(this).attr("href");
    });
    
    function openConfirmModal() {
    	
        $("#confirmDiv").confirmModal({
			heading:'Are you sure?',
			body:"Sure you want to delete?<br><br>",
            callback: function () {
            	doHostDelete();
            }
        });
    }

	
    function doHostDelete()
    {
		var post_data = {
				id: delete_target,
				csrf_token_name: $.cookie('csrf_cookie_name')
			};

			$.post( SITE_URL +'hosts/delete_host', post_data, handleDeleteInstResponse);		
    }


    function handleDeleteInstResponse(data)
    {
    	console.log(data);
    	if(data && data.success) {
    		initializeHosts();
    		if($(".host-deleted").is(":visible")) {
    			$(".host-deleted").slideUp();
    		}
    		$(".host-deleted").slideDown();
    	}
    }
    