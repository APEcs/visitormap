<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script src="<?php print site_url();?>js/user-form-validation.js"></script>
<script src="<?php print site_url();?>js/pGenerator.jquery.js"></script>

<div class="container main-content">
	<hr>
<div class="row">
<div class="span12 ">
  <?php
  $attributes = array('class'=> "form-horizontal well", 'id' => "new_user_form");
  echo form_open('users/do-edit-user', $attributes);
  ?>
        <fieldset>
          <legend>UNDER CONSTUCTION == Edit your account details <small>(all fields are compulsory)</small></legend>
          <div class="control-group">
            <label class="control-label" for="first_name">First Name</label>
            <div class="controls">
              <input type="text" class="input" id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>">
              <span class="help-inline"><small>Given Name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="last_name">Last Name</label>
            <div class="controls">
              <input type="text" class="input" id="last_name" name="last_name" value="<?php echo set_value('last_name'); ?>">
              <span class="help-inline"><small>Familiy name</small></span>
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
              <span class="help-inline"><small>This will be the username</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="email_repeat">Repeat E-mail</label>
            <div class="controls">
              <input type="text" class="input" id="email_repeat" name="email_repeat" value="<?php echo set_value('email_repeat'); ?>">
              <span class="help-inline"><small></small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="user_type">User Type</label>
            <div class="controls">
				<select class="input" id="user_type" name="user_type">
				  <option value="1" <?php echo set_select('user_type', '1'); ?> >Staff Member</option>
				  <option value="2" <?php echo set_select('user_type', '2'); ?> >Administrator</option>
				  <option value="3" <?php echo set_select('user_type', '3'); ?> >Director</option>
				</select>
              <span class="help-inline"><small></small></span>
            </div>
          </div>


 		  <?php echo "<p>". validation_errors(). "</p>";
		  if (isset($error)) echo "<p>". $error. "</p>";
		  ?>

          <div class="form-actions">
            <button type="submit" class="btn btn-success">Save Changes</button>
            <a type="btn" href="<?php print site_url();?>user_list" class="btn">Cancel</a>
          </div>

        </fieldset>
      </form>
</div>
</div>
	</div>
