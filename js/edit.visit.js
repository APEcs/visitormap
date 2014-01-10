

$(document).ready(function(){
	var selected_inst = $('#institution').select2("val");

	if($.getUrlVar('inst_edit_success')==1) {
		$("#institution_message").append("<span class='label label-success label-saved'>Saved</span>").show().delay(2000).fadeOut(1000);
	}
	if($.getUrlVar('dep_edit_success')==1) {
		$("#department_message").append("<span class='label label-success label-saved'>Saved</span>").show().delay(2000).fadeOut(1000);
	}
	
	if($.getUrlVar('origin')=='home') {
		$("input[name='origin']").val("home");
	}	
	
	if($.getUrlVar('inst_id')) {
		$("#institution").select2("val", $.getUrlVar('inst_id'));
		var post_data = { 'id': $.getUrlVar('inst_id'), 'csrf_token_name' : $.cookie('csrf_cookie_name') };
		$.post( SITE_URL +'departments/get_departments', post_data, handleDepartmentsAJAXResponse);
	}
	
	if($('#department :selected').val()==0) {
		$('#edit_department').hide();
	}

	$('#edit_institution').attr('href', SITE_URL +'institutions/edit_institution?' + 
	 	'id=' + $('#institution').select2("val") +
	 	'&origin=edit_visit' +
	 	'&origin_id=' + $.getUrlVar('id'));

	$('.trigger-warning').change(function() {
		if(!$("#name-warning").is(":visible")) {
			$("#name-warning").slideDown();
			$("#person_changed").val('1');
		}
	});
	
	$("#institution").on("change", function(e) {	
		$('#edit_institution').attr('href', SITE_URL +'institutions/edit_institution?' +
			'id=' + e.val +
			'&origin=edit_visit' +
	 		'&origin_id=' + $.getUrlVar('id'));
	 	
	 	selected_inst = e.val;	
	 	$('#edit_department').hide();		
	});
	
	$("#department").on("change", function(e) {
		if($('#department').select2("val")!=0) {
			$('#edit_department').show();
			
			$('#edit_department').attr('href', SITE_URL + 'departments/existing_departments?' +
	 			'inst_id=' + selected_inst +
	 			'&dep_id=' + e.val +
	 			'&origin=edit_visit' +
	 			'&origin_id='+ $.getUrlVar('id')); 
	 			
	 		$('#edit_institution').attr('href', SITE_URL +'institutions/edit_institution?' +
				'id=' + selected_inst +
				'&origin=edit_visit' +
	 			'&origin_id=' + $.getUrlVar('id') +
	 			'&dep_id=' + e.val);
		}
		else {
			$('#edit_department').hide();
		}
	});
	
	$("#research_group").on("change", function(e) {		
		$('#editGroupBtn').attr('href', e.val); 
	});
	
	$("#host").on("change", function(e) {		
		$('#editHostBtn').attr('href', e.val); 
	});	
	
	
	$("#editGroupBtn").on("click", function(e) {
		e.preventDefault();
    	prepareEditGroup($(this).attr("href")); //this method is in existing.research.groups.js
	});
	
	$("#editHostBtn").on("click", function(e) {
		e.preventDefault();
    	prepareEditHost($(this).attr("href")); //this method is in existing.hosts.js
	});

});

