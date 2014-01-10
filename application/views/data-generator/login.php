<?php $this->load->helper('form'); ?>

<script type="text/javascript">

<!-- ############ New User Form validation JavaScript ############## -->


$(document).ready(function(){

	  var $err_open = "<div class='text-error'><small>";
	  var $err_close = "</small></div>";
	  $("#email").focus();

  $("#login-form").validate({



rules: {
	password: {
		required: true,
	},
  email: {
    required: true,
    email: true
  },
},

messages: {
   password: {
       required: $err_open + "Enter your password, duh!" + $err_close,
     },
   email: {
     required: $err_open + "Enter your e-mail, duh!" + $err_close,
     email: $err_open +"That aint valid e-mail, try again!" + $err_close
   },
 }
});
});s
<!-- ############ End of Form validation JavaScript ############## -->



</script>

	<div class="container">

		<div class="hero-unit">
		  <h1>Data Generator.</h1>
		  <p>Welcome to Karl Kerem's webpage, where you can generate pseudo data about researchers visiting the University of Manchester's School of Computing</p>
		  <p>
		  <?php
		  $attributes = array('class' => '', 'id' =>"login-form");
		  echo form_open('data-generator/login/action', $attributes);
		  ?>
			  <input type="text" class="input" name="email" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
			  <br>
			  <input type="password" class="input" id="password" name="password" placeholder="Password">
			  <br>
			  <button type="submit" name="action" value="sign_in" class="btn btn-small">Sign in</button>
			  <button type="submit" name="action" value="create_new_user" class="btn btn-small">Create New User</button>
			</form>

		  </p>
		  <?php echo "<p><small>". validation_errors(). "</small></p>";
		  if (isset($error)) echo "<p><small>". $error. "</small></p>";
		  ?>

		</div>
	</div>
