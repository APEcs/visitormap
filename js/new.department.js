<!-- ############ JavaScript ############## -->


function saveDepartment()
{
	
	if($("#new-department-form").validate().form()) {
		var post_data = {
				institution_id: $('#institution :selected').val(),
				name:  $("#department_name").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};

			$.post( SITE_URL +'departments/new_department', post_data, handleDepartmentResponse);		
		}
	return false;
}


function handleDepartmentResponse(data)
{
	if(data) {
		$("#department").append("<option value="+data.id+">"+data.name+"</option>");
		$("#department").select2("val", data.id);
		
		
		$("#department_name").val("");
		
		$('#new-department').modal('hide')
	}
}


<!-- ############ New Department Form validation ############## -->

$(document).ready(function(){

	$("#inst_name_for_new_dep").val($('#institution :selected').html());

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";
	  
	$("#new-department-form").validate({         
		
		errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		  },      
		  
		rules: {  
			department_name: "required"
		},
		
		messages: {
			group_name: $err_open + "Department Name is required" + $err_close
		 }
	});
});
<!-- ############ End of Form validation ############## -->




<!-- ############ End of JavaScript ############## -->
