var openVisitId;

function populateReportProblem(data){
    
    cleanUp();	
    
    if(data) {
    
    	openVisitId = data.id;
    	$("#prob-institution").append(data.institution);
    	if(!data.department) {
    		$("#prob-department").append("Not Specified");
    	}
    	else {
    		$("#prob-department").append(data.department);
    	}
    	$("#prob-country").append("<img src='"+SITE_URL+"img/blank.gif' class='flag flag-"+data.country_iso+"' alt='"+data.country+"' />"+data.country);
    	$("#prob-visitor").append(data.visitor_title +" "+ data.visitor_first_name + " " + data.visitor_last_name);
    	$("#prob-visitor-gender").append(data.visitor_sex.substr(0,1).toUpperCase()+data.visitor_sex.substr(1));
    	$("#prob-visiting-position").append(data.position);
    	$("#prob-host").append(data.host_title +" "+ data.host_first_name + " " + data.host_last_name);
    	$("#prob-group").append(data.group);
    	$("#prob-from-date").append(data.from_date.format("yyyy-mm-dd"));
    	$("#prob-to-date").append(data.to_date.format("yyyy-mm-dd"));
 
 		//if person has rights to edit visits
 		if ($("#link-to-existing-visits").length){
 			$(".report-button").hide();
 			$(".change-pass-actions").css("margin-top", "0px"); 
 			$(".report").after(
 				'<a href="'+SITE_URL+'visits/edit_visit?id='+data.id+'&origin=home" id="edit-open-visit" type="btn" class="btn btn-problem-edit btn-info">Edit</a>');
 		}
    	$('#report-problem').modal('show');
    }	
}

function cleanUp(){
	$("#prob-institution").empty();
	$("#prob-department").empty();
	$("#prob-country").empty();
	$("#prob-visitor").empty();
	$("#prob-visitor-gender").empty();
	$("#prob-visiting-position").empty();
	$("#prob-host").empty();
	$("#prob-group").empty();
	$("#prob-from-date").empty();
    $("#prob-to-date").empty();
    $("#problem-report-text").val("");
    
    $(".report").hide();
    $(".problem-alert").remove();
    $("#edit-open-visit").remove();
    $("#send-report-btn").hide();
    $(".report-button").show();
    $(".change-pass-actions").css("margin-top", "41px"); 
    $('#report-problem').css('top', ''); 
}


function sendReport()
{
	if($("#report-problem-form").validate().form()) {
		
		
		var post_data = {
				visit_id: openVisitId,
				problem_text: $("#problem-report-text").val(),
				problem_reporter_email: $("#problem-email").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};
		$.post( SITE_URL+"home/report_problem", post_data, handleReportResponse);
		
		}
	return false;
}


function handleReportResponse(data) {

    $(".report").hide();
    $("#send-report-btn").hide();
		
	if(data.success) {
		$(".report").after(
		"<span class='alert alert-success problem-alert'>Thank you! A notification is sent to the administrator. </span>");
	}
	else {
		$(".report").after(
		"<span class='alert alert-error problem-alert'>Oh Snap! Something went wrong. Please try again later.</span>");
	}
}



$(document).ready(function(){

    $("#report-open").on("click", function (e) {
    	if($(window).width() > 979) {
    		$('#report-problem').css('top', '40%');
    		}
    	e.preventDefault();
    	$(".report-button").hide();
    	$(".report").show();
    	$("#send-report-btn").show();
    	$(".change-pass-actions").css("margin-top", "0px");
   	});

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";
	  
	$("#report-problem-form").validate({            
		  
		rules: {  
			'problem-report-text': "required",
			'problem-email': {
      			required: true,
      			email: true
    			},
		},
		
		messages: {
			'problem-report-text': "",
			'problem-email':  {
       			required: $err_open + "Please specify your e-mail" + $err_close,
       			email: $err_open +"Not valid e-mail" + $err_close
     			}			
		 }
	});
});

//function for disabling default enter keypress
$(document).keypress(function(e) {
    if($("#problem-email").is(":focus") && e.which == 13) {
    	e.preventDefault();
    	sendReport();
    }
});
