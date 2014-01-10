<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<script src="<?php print site_url();?>js/data-generator/user-form-validation.js"></script>

	<div class="container">	

<div class="span10 offset1">
<h1>Data Generator.</h1>
  <?php 
  $attributes = array('class'=> "form-horizontal well", 'id' => "new_user_form");
  echo form_open('data-generator/createUser', $attributes);
  ?>
        <fieldset>
          <legend>Create new user</legend>
          <div class="control-group">
            <label class="control-label" for="first_name">First Name</label>
            <div class="controls">
              <input type="text" class="input" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>">
              <span class="help-inline"><small>Your Given Name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="last_name">Last Name</label>
            <div class="controls">
              <input type="text" class="input" id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>">
              <span class="help-inline"><small>Your Familiy name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="password">Password</label>
            <div class="controls">
              <input type="password" class="input" id="password" name="password" value="<?php if(isset($password)) { echo $password;} else { echo set_value('password'); } ?>" >
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
          <div class="control-group">
            <label class="control-label" for="email">E-mail</label>
            <div class="controls">
              <input type="text" class="input" id="email" name="email" value="<?php if(isset($email)) { echo $email; } else { echo set_value('email'); } ?>" >
              <span class="help-inline"><small>This will be your username</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="email_repeat">Repeat E-mail</label>
            <div class="controls">
              <input type="text" class="input" id="email_repeat" name="email_repeat" value="<?php echo set_value('email_repeat'); ?>">
              <span class="help-inline"><small></small></span>
            </div>
          </div>         
          
 		  <?php echo "<p>". validation_errors(). "</p>"; 
		  if (isset($error)) echo "<p>". $error. "</p>";
		  ?>         
            
<!--           <div class="control-group error"> -->
<!--             <label class="control-label" for="inputError">Input with error</label> -->
<!--             <div class="controls"> -->
<!--               <input type="text" id="inputError"> -->
<!--               <span class="help-inline">Please correct the error</span> -->
<!--             </div> -->
<!--           </div> -->

          <div class="form-actions">
            <button type="submit" class="btn btn-success">Create new User</button>
            <a type="btn" href="<?php print site_url();?>data-generator" class="btn">Cancel</a>
          </div>
        </fieldset>
      </form>		
</div>		
		
	</div>
		
	

