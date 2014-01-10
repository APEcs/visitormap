<!-- ############ New User Form validation JavaScript ############## -->




  $(document).ready(function(){
  
  
      
    $("#report-open").on("click", function (e) {
    	e.preventDefault();
    	$(".report-button").hide();
    	$(".report").show();
    	$("#send-report-btn").show();
    	$(".change-pass-actions").css("margin-top", "0px");
   	});

	  var $err_open = "<div class='text-error'><small>";
	  var $err_close = "</small></div>";
	  
    $("#new_user_form").validate({         

  errorPlacement: function(error, element) {
	error.insertAfter(element.next());
    },      
    
  rules: {  
	first_name: "required",
	last_name: "required",
	password: {
		required: true,
		minlength: 6
	},
	password_repeat: {
		equalTo: "#password"
	},
    email: {
      required: true,
      email: true
    },
    email_repeat: {
    	equalTo: "#email"
      }    
  },
  
  messages: {
	 first_name: $err_open + "First name is missing!" + $err_close,
	 last_name: $err_open + "Last Name is missing!" + $err_close,
     password: {
         required: $err_open + "Enter the password, duh!" + $err_close,
         minlength: $err_open +"At least 6 characters are required!" + $err_close
       },
       password_repeat: {
    	 equalTo: $err_open + "Passwords don't match!" + $err_close
     },
     email: {
       required: $err_open + "We need an email address to contact the new user" + $err_close,
       email: $err_open +"Email address must be in the format of name@domain.com" + $err_close
     },
     email_repeat: {
    	 equalTo: $err_open + "E-mails don't match!" + $err_close
     },     
   }
});
  });
<!-- ############ End of Form validation JavaScript ############## -->