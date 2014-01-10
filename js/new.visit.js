<!-- ############ Info Popovers ############## -->
$(function ()  {
	
	
	$('#institution_popover').popover({
		placement: 'bottom',
		title: 'Home institution',
		content: 'Specify from which Institution or University the visitor is from.<br> Click on "Add New" button to create a new Institution.'
		});	
	$('#research_popover').popover({
		placement: 'bottom',
		title: 'Research Group',
		content: 'Specify which Research Group is hosting the visitor.<br> Click on "Add New" button to create a new Research Group.'
		});
	$('#host_popover').popover({
		placement: 'bottom',
		title: 'Hosting Person',
		content: 'Specify who is hosting the guest. <br> Click on "Add New" button to create a new Host.'
		});	

	$( "#datepicker_from" ).datepicker({
	      firstDay: 1,
	      dateFormat: "dd.mm.yy"
	    });	
	$( "#datepicker_to" ).datepicker({
	      firstDay: 1,
	      dateFormat: "dd.mm.yy"
	    });   	
});
<!-- ############ End of Info Popovers ############## -->


$(function () {
	var cct = $("input[name=csrf_token_name]").val();
	
	$("#returning_guest").select2({
	allowClear: true,
	width: "224px"
	});
	$("#institution").select2();
	$("#research_group").select2();
	$("#host").select2();
	$("#visiting_position").select2();
	$("#department").select2();
	
	$("#returning_guest").on("change", function(e) { 
		
		if(e.val)
		{
			$("#first_name").prop('readonly', true);
			$("#last_name").prop('readonly', true);
			$("#title").prop('readonly', true);	

			var post_data = { id: e.val, 'csrf_token_name': cct };
			$.post( SITE_URL +'visits/get_data_about_returning_visitor', post_data, handleReturningVisitorResponse);
			
		}
		else
		{
			$("#first_name").prop('readonly', false).val("");
			$("#last_name").prop('readonly', false).val("");
			$("#title").prop('readonly', false).val("");	
			$('.sex').attr('disabled', false);
			$("#returning_guest_id").val("");	
		}
	
		});

	
	$("#institution").on("change", function(e) { 
		
		$('#department').find('option').remove().end()
	    .append('<option value="0">No Department/School</option>')
	    .val('0');
		$("#department").select2("val", "0");
		$("#inst_name_for_new_dep").val($('#institution :selected').html());
		
		if(!$("#new_department_button").is(":visible")) {
			$("#new_department_button").toggle();
		}
		
		var post_data = { 'id': e.val, 'csrf_token_name': cct };
		$.post( SITE_URL +'departments/get_departments', post_data, handleDepartmentsAJAXResponse);
	
		});	
});

function handleReturningVisitorResponse(data)
{
	$("#first_name").val(data.first_name);
	$("#last_name").val(data.last_name);
	if(data.title) $("#title").val(data.title);
	$("#returning_guest_id").val(data.id);
	
	if(data.sex) {
		$('.sex').attr('disabled', false);
		$('#'+data.sex).click();
		$('.sex:radio:not(:checked)').attr('disabled', true);
	 }	
}

function handleDepartmentsAJAXResponse(data) {
	var show_edit_department_button = false;
	if(data) {
		$.each(data, function (i, item) {
			$('#department').append($('<option>', { 
		        value: item.id,
		        text : item.name 
		    }));
		    if(item.id === $.getUrlVar('dep_id')) {
		    	show_edit_department_button = true;
		    }
		});
		if(show_edit_department_button) {
			$("#department").select2("val", $.getUrlVar('dep_id'));
			$('#edit_department').show();
		}
	}
}


<!-- ############ New Visit Form validation JavaScript ############## -->

function validateVisitForm(){
	$("#new_visit_form").validate().form();
	}


  $(document).ready(function(){

	jQuery.validator.addMethod("dateNL", function(value, element) {
		return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
	}, "Please enter a correct date");
	  
	  var $err_open = "<div class='text-error'><small>";
	  var $err_close = "</small></div>";
	  
    $("#new_visit_form, #edit_visit_form").validate({         

  errorPlacement: function(error, element) {
	error.insertAfter(element.next());
    },      
    
  rules: {  
	title: {
		required: true,
		maxlength: 20
	},
	first_name: {
		required: true,
		maxlength: 100
	},
	last_name: {
		required: true,
		maxlength: 100
	},
	institution: {
		required:true,
	},
	from_date: {
      	required: true,
      	dateNL: true
    },
	to_date: {
      	required: true,
      	dateNL: true
    }	    
  },
  
  messages: {
     first_name: {
         required: $err_open + "First name is missing!" + $err_close,
         maxlength: $err_open +"First name can't be longer than 100 characters" + $err_close
       },	 
     last_name: {
         required: $err_open + "Last name is missing!" + $err_close,
         maxlength: $err_open +"Last name can't be longer than 100 characters" + $err_close
       },
     institution: $err_open + "Institution is required" + $err_close,
     title: {
         required: $err_open + "Title is required" + $err_close,
         maxlength: $err_open +"Title can't be longer than 20 characters" + $err_close
       },
     from_date: {
     	required: $err_open + "From Date is required" + $err_close,
     	dateNL: $err_open + "Please enter the date in dd.mm.yyyy format" + $err_close
     },
     to_date: {
     	required: $err_open + "To Date is required" + $err_close,
     	dateNL: $err_open + "Please enter the date in dd.mm.yyyy format" + $err_close
     } 
   }
});
  });
<!-- ############ End of Form validation JavaScript ############## -->