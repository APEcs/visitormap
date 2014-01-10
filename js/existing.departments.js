var err_open = "<div class='text-error text-error-inst'><small>";
var err_close = "</small></div>";
var selected_inst;

$(function () {
	
	$("#institution").select2({
		width: "284px"
	});
	
	if($.getUrlVar('inst_id')) {
		selected_inst = $.getUrlVar('inst_id');
		$("#institution").select2("val", selected_inst);
		getDepartments();
	}
	
	if($.getUrlVar('origin')=="edit_visit") {
		$("#institution").select2("disable");
	}

	$("#institution").on("change", getDepartments);	
		
});

function getDepartments(e) {
	if(e) {
		selected_inst = e.val;
	} 
	var post_data = { 'id': selected_inst, 'csrf_token_name': $.cookie('csrf_cookie_name') };
	$.post( SITE_URL +'departments/get_departments', post_data, handleDepartmentsAJAXResponse);

		if(!$("#dynamic-content").is(":visible")) {
		$("#dynamic-content").toggle();
	}
	if(!$("#add-department-btn").is(":visible")) {
		$("#add-department-btn").toggle();
	}
	
	$('#departments').empty();
	
}


function handleDepartmentsAJAXResponse(data) {
	$('#departments').toggle();
	if(data) {
		$.each(data, function (i, item) {
			var dep_line = "";
			dep_line += '<div id="'+item.id+'-controls" class="controls">' +
						'<input class="input-xlarge" delete="0" type="text" name="'+item.id+'" id="dep_'+item.id+'" value="'+item.name+'">';
			if(item.no_of_visits==0) {
				dep_line += '<button  type="button" id="btn_'+item.id+'" style="cursor: pointer;" onclick="deleteDepartment(\''+item.id+'\')" class="btn btn-danger department-delete-btn">Delete</button>';
			}
			else {
				dep_line += '<span class="help-inline"><small> You can only delete departments without visits. (It has '+item.no_of_visits+')</small></span>';
			}
			dep_line +=	'</div>';		
			
			$('#departments').append($(dep_line));
				
			$("#dep_"+item.id).rules("add", {
				required: true,
 				maxlength: 220,
 				messages: {
    				required: err_open+ "Required input" + err_close,
    				maxlength: err_open+ "This is way too long!" + err_close
 				}
			});	
			
		});
	}
	$('#departments').slideDown();
}


function addDepartmentField() {

	$("#add-department-btn").toggle();
	$('#departments').append(
			$('<div id="new-controls" style="display: none" class="controls">' +
			'<input class="input-xlarge" type="text" delete="0" name="new" id="dep_new" placeholder="New Department/School">' +
			'<button  type="button" id="btn_new" style="cursor: pointer;" onclick="deleteDepartment(\''+"new"+'\')" class="btn btn-danger department-delete-btn">Delete</button>' +
			'</div>'));
	$('#new-controls').slideDown();
	
	
	$("#dep_new").rules("add", {
		required: true,
 		maxlength: 220,
 		messages: {
    		required: err_open+ "Required input" + err_close,
    		maxlength: err_open+ "This is way too long!" + err_close
 		}
	});
	
}


function deleteDepartment(id) {
	if(id=="new") {
		$('#'+id+'-controls').slideUp(300, function(){
   			$(this).remove();
   			if(!$("#add-department-btn").is(":visible")) {
				$("#add-department-btn").toggle();
			}
		});	
	}
	else {
		$('#dep_'+id).prop('disabled', true);
		$('#dep_'+id).attr('delete', "1");
		$('#btn_'+id).replaceWith('<button  type="button" id="btn_undo_'+id+'" style="cursor: pointer;" onclick="undoDelete(\''+id+'\')" class="btn department-delete-btn">Undo</button>');
		
	}
}

function undoDelete(id) {
	$('#dep_'+id).prop('disabled', false);
	$('#dep_'+id).attr('delete', "0");
	$('#btn_undo_'+id).replaceWith('<button  type="button" id="btn_'+id+'" style="cursor: pointer;" onclick="deleteDepartment(\''+id+'\')" class="btn btn-danger department-delete-btn">Delete</button>' );
}



function saveChanges(){
	if($("#departments-form").validate().form() && selected_inst) {
	
		$('[id^=dep_]').each(function(i, item) {
			var post_data = {
				delete: $(item).attr("delete"),
				institution_id: selected_inst,
				id:  $(item).attr("name"),
				name: $(item).val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};
			$.post( SITE_URL +'departments/do_edit_department', post_data, handleAJAXResponse).error(function() { 
  		 		handleAJAXError(item.attr("name")); 
				});
		});
	}	
}


function handleAJAXResponse(data)
{
	if(data && $.getUrlVar('origin') == 'edit_visit') {
		window.location = SITE_URL + 'visits/edit_visit?' +
			'id=' + $.getUrlVar('origin_id') + '&dep_edit_success=1' +
			'&inst_id=' + $.getUrlVar('inst_id') +
			'&dep_id=' + $.getUrlVar('dep_id');
		return null;
	}
	if(data && data.success) {
		if($("#dep_"+data.id).next().length != 0) {
			$("#dep_"+data.id).next().after("<span class='label label-success label-saved'>Saved</span>");
		}
		else {
			$("#dep_"+data.id).after("<span class='label label-success label-saved'>Saved</span>");
		}
		
	}
	if (data && data.success_delete) {
		$("#dep_"+data.id).next().after("<span class='label label-important label-saved'>Deleted</span>");
		$('#'+data.id+'-controls').delay(2000).slideUp(400, function(){
   			$(this).remove();
		});
	}
}


function cancelChanges() {
	if($.getUrlVar('origin') == 'edit_visit') {
		window.location = SITE_URL + 'visits/edit_visit?' +
			'id=' + $.getUrlVar('origin_id') +
			'&inst_id=' + $.getUrlVar('inst_id') +
			'&dep_id=' + $.getUrlVar('dep_id');
	}
	else {
		getDepartments();
	}
}

  $(document).ready(function(){
	  
	$("#departments-form").validate({         
		errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		  }
	});
	  
  });
