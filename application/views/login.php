<?php $this->load->helper('form'); ?>

<script type="text/javascript">

<!-- ############ Login Form validation JavaScript ############## -->


function showNewUserModal(e) {
	$('#new_user').modal('show');
}

$(document).ready(function(){

	$("#loginLink").on("click", function (e) {
		e.preventDefault();
		$('#myModal').modal('show');

		$('#myModal').on('shown', function () {
			$("#email").focus();
			});

	});


  var $err_open = "<div class='text-error'><small>";
  var $err_close = "</small></div>";
	  $("#email").focus();

<?php if (isset($login_error)) echo "$('#myModal').modal('show')";?>

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
     required: $err_open + "That ain't valid e-mail!" + $err_close,
     email: $err_open +"That ain't valid e-mail!" + $err_close
   },
 }
});
});
<!-- ############ End of Form validation JavaScript ############## -->

</script>


	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style='display: none;' >

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Login to CS Research Visitors Map</h3>
		</div>

		<div class="modal-body">
			<div class="login-container">
			  <?php
			  $attributes = array('class' => '', 'id' =>"login-form");
			  echo form_open('home/login', $attributes);
			  ?>
				  <input type="text" class="input" name="email" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
				  <br>
				  <input type="password" class="input" id="password" name="password" placeholder="Password">
				  <br>
				  <button type="submit" name="action" value="sign_in" class="btn btn-mini btn-login">Login</button>
				</form>

			  </p>
			  <?php echo "<p><small>". validation_errors(). "</small></p>";
			  if (isset($login_error)) echo "<p><small>". $login_error. "</small></p>";
			  ?>
			</div>
		</div>

	</div>



	<div class="modal fade" id="new_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Need access to CS Research Visitors Map?</h3>
		</div>

		<div class="modal-body">
			<p>To get your very own username and password, please contact john.smith@manchsester.ac.uk</p>

		</div>

	</div>
