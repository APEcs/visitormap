<?php $this->load->helper('form'); ?>
<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>

<script type="text/javascript">

<!-- ############ New User Form validation JavaScript ############## -->


$(document).ready(function(){

  var $err_open = "<div class='text-error'><small>";
  var $err_close = "</small></div>";

$("#password").focus();
		  
$('#change-password-modal').modal({
	backdrop: 'static',
	keyboard: false
	});

$('#change-password-modal').modal('show');


$("#login-form").validate({         

  errorPlacement: function(error, element) {
		error.insertAfter(element.next());
	    }, 	  
  
rules: {  
	password: {
		required: true,
		minlength: 6
	},
	password_repeat: {
		equalTo: "#password"
	}
  },   


messages: {
    password: {
        required: $err_open + "Enter the password, duh!" + $err_close,
        minlength: $err_open +"At least 6 characters are required!" + $err_close
      },
      password_repeat: {
   	 equalTo: $err_open + "Passwords don't match!" + $err_close
    },    
  }
});
});
<!-- ############ End of Form validation JavaScript ############## -->

</script>


	<div class="modal bigModal" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="change-password-modal-label" aria-hidden="true" style='display: none;' >
		
		<div class="modal-header">
			<h3 id="change-password-modal-label">Change your password</h3><br>
			<p>This is your first time to log in.<br> For security reasons you need to change your default password.</p>
		</div>
		
		<div class="modal-body">
			<div class="change-pass-container">
			  <?php 
			  $attributes = array('class' => '', 'id' =>"login-form");
			  echo form_open('users/change_password', $attributes);
			  ?>
				<fieldset>
		          <legend>New Password</legend>

		          <div class="control-group">
		            <label class="control-label" for="password">Password</label>
		            <div class="controls">
		              <input type="password" class="input" id="password" name="password" >
		           	  <span class="help-inline"><small>At least 6 characters</small></span>
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="password_repeat">Repeat Password</label>
		            <div class="controls">
		              <input type="password" class="input" id="password_repeat" name="password_repeat">
		              <span class="help-inline"><small></small></span>
		            </div>
		          </div>       
		
		          <div class="change-pass-actions">
		            <button type="submit" class="btn btn-success">Save new password</button>
		            <a type="btn" href="<?php print site_url();?>home/logout" class="btn">Log Out</a>
		          </div>
				</form>		
			  	
			  </p>
			  <?php echo "<p><small>". validation_errors(). "</small></p>"; 
			  if (isset($login_error)) echo "<p><small>". $login_error. "</small></p>";
			  ?>
			</div>
		</div>
		
	</div>
		

