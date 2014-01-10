<!-- ############ JavaScript ############## -->


function saveHost()
{
	if($("#new-host-form").validate().form()) {
		
		var post_data = {
				host_id: $("input[name=host_id]").val(),
				host_title: $("#host_title").val(),
				host_first_name: $("#host_first_name").val(),
				host_last_name: $("#host_last_name").val(),
				host_sex: $("#new-host-form input[type='radio']:checked").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};

		if($("input[name=host_id]").val()) {
			var method = "hosts/do_edit_host";
		}
		else {
			var method = "hosts/new_host";
		}
		$.post( SITE_URL+method, post_data, handleHostResponse);
		}
	return false;
}


function handleHostResponse(data)
{
	
	//if we are in new visit page
	if($(location).attr('href').indexOf("new_visit") >= 0) {
		$("#host").append("<option value="+data.id+">"+data.name+"</option>");
		$("#host").select2("val", data.id);
		}

	//if we are in edit visit page
	if($(location).attr('href').indexOf("edit_visit") >= 0) {
		$('#host option[value='+$("input[name=host_id]").val()+']').text(data.name);
		$("#host").select2("val", $("input[name=host_id]").val());
		$("#host_message").append("<span class='label label-success label-saved'>Saved</span>").show().delay(2000).fadeOut(1000);
		}

	//if we are in existing_institutions page
	if($(location).attr('href').indexOf("existing_hosts") >= 0) {
		initializeHosts();
		
		if(data.update_success) {
			if($(".host-updated").is(":visible")) {
				$(".host-updated").slideUp();
				}
				$(".host-updated").slideDown();
		}
		else {
			if($(".new-host-entered").is(":visible")) {
				$(".new-host-entered").slideUp();
				}
				$(".new-host-entered").slideDown();
		}
	}

	$('#new-host').modal('hide')
	cleanModalFields();
}

function cleanModalFields(){
	$("input[name=host_id]").val("");
	$("#host_title").val("");
	$("#host_first_name").val("");
	$("#host_last_name").val("");
}

<!-- ############ New Host Form validation ############## -->

$(document).ready(function(){

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";
	  
	$("#new-host-form").validate({         
		
		errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		  },      
		  
		rules: {  
			host_title: "required",
			host_first_name: "required",
			host_last_name: "required"
		},
		
		messages: {
			host_title: $err_open + "Title is required" + $err_close,
			host_first_name: $err_open + "First Name is required" + $err_close,
			host_last_name: $err_open + "Last Name is required" + $err_close			
		 }
	});
});
<!-- ############ End of Form validation ############## -->




<!-- ############ End of JavaScript ############## -->
