<!-- ############ JavaScript ############## -->


function saveGroup()
{
	if($("#new-research-group-form").validate().form()) {
		var post_data = {
				group_id: $("input[name=group_id]").val(),
				group_acronym: $("#group_acronym").val(),
				group_name:  $("#group_name").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};
		if($("input[name=group_id]").val()) {
			var method = "research_groups/do_edit_group";
		}
		else {
			var method = "research_groups/new_research_group";
		}

			$.post( SITE_URL+method, post_data, handleGroupResponse);		
		}
	return false;
}


function handleGroupResponse(data)
{

	//if we are in new visit page
	if($(location).attr('href').indexOf("new_visit") >= 0) {
		$("#research_group").append("<option value="+data.id+">"+data.name+"</option>");
		$("#research_group").select2("val", data.id);
		}

	//if we are in edit visit page
	if($(location).attr('href').indexOf("edit_visit") >= 0) {
		$('#research_group option[value='+$("input[name=group_id]").val()+']').text($("#group_name").val());
		$("#research_group").select2("val", $("input[name=group_id]").val());
		$("#research_group_message").append("<span class='label label-success label-saved'>Saved</span>").show().delay(2000).fadeOut(1000);
		}

	//if we are in existing_institutions page
	if($(location).attr('href').indexOf("existing_groups") >= 0) {
		initializeGroups();
		
		if(data.update_success) {
			if($(".group-updated").is(":visible")) {
				$(".group-updated").slideUp();
				}
				$(".group-updated").slideDown();
		}
		else {
			if($(".new-group-entered").is(":visible")) {
				$(".new-group-entered").slideUp();
				}
				$(".new-group-entered").slideDown();
		}
	}	
	
	cleanModalFields();
	$('#new-research-group').modal('hide')

}

function cleanModalFields(){
	$("input[name=group_id]").val("");
	$("#group_acronym").val("");
	$("#group_name").val("");
}

<!-- ############ New Research Group Form validation ############## -->

$(document).ready(function(){

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";
	  
	$("#new-research-group-form").validate({         
		
		errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		  },      
		  
		rules: {  
			group_acronym: "required",
			group_name: "required"
		},
		
		messages: {
			group_acronym: $err_open + "Group Acronym is required" + $err_close,
			group_name: $err_open + "Group Name is required" + $err_close
		 }
	});
});
<!-- ############ End of Form validation ############## -->




<!-- ############ End of JavaScript ############## -->
