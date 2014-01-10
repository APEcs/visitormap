<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<script src="<?php print site_url();?>js/new.visit.js"></script>
<script src="<?php print site_url();?>js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?php print site_url();?>js/select2.min.js"></script>

<div class="container main-content">	
	<hr>
<div class="row">
<div class="span12 ">
  <?php 
  if(isset($new_visit_success)) 
  { ?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
 		<strong>Success!</strong> New visit was saved.
 	</div>
  <?php
  }
  $attributes = array('class'=> "form-horizontal well", 'id' => "new_visit_form");
  echo form_open('visits/do_new_visit', $attributes);
  ?>
        <fieldset>
          <legend>Create new visit <small>(fields with * are compulsory)</small></legend>
          <?php echo "<p>". validation_errors(). "</p>"; 
		  if (isset($error)) echo "<p>". $error. "</p>";
		  ?>
          <div class="control-group">
            <label class="control-label" for="returning_guest">Returning Guest?</label>
            <div class="controls">
				<select class="input input-large" id="returning_guest" name="returning_guest" data-placeholder="Choose Guest">
					<option value=""></option>
				<?php foreach($returning_guests as $guest) 
				{ 
				echo "<option value='".$guest['id']."' " .set_select('returning_guest', $guest['id']). ">".$guest['first_name']." ".$guest['last_name']."</option>";			
				}?>
				</select>
              <span class="help-inline"><small>Search for existing guest</small></span>
            </div>
          </div> 		 
		 <div class="control-group">
            <label class="control-label" for="title">Title *</label>
            <div class="controls">
              <input type="text" class="input input-small" placeholder="Dr/Mr/Ms/etc." id="title" name="title" value="<?php echo set_value('title'); ?>">
              <span class="help-inline"><small></small></span>
            </div>
          </div>  
          <div class="control-group">
            <label class="control-label" for="first_name">First Name *</label>
            <div class="controls">
              <input type="text" class="input" id="first_name" placeholder="John" name="first_name" value="<?php echo set_value('first_name'); ?>">
              <span class="help-inline"><small>Given Name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="last_name">Last Name *</label>
            <div class="controls">
              <input type="text" class="input" id="last_name" placeholder="Smith" name="last_name" value="<?php echo set_value('last_name'); ?>">
              <span class="help-inline"><small>Familiy name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="public">Is visitor's name public?</label>
            <div class="controls">
				<label class="radio">
				  <input type="radio" name="public" id="public_yes" value="1" <?php echo set_radio('public', '1'); ?> >
				  <b>Yes</b>, everybody can see visitor's name
				</label>
				<label class="radio">
				  <input type="radio" name="public" id="public_no" value="0" <?php echo set_radio('public', '0', TRUE); ?>  >
				  <b>No</b>, only authenticated users can see visitor's name
				</label>
            </div>
          </div>           
          <div class="control-group">
            <label class="control-label" for="sex">Sex</label>
            <div class="controls">
				<label class="radio inline">
				  <input type="radio" name="sex" class="sex" id="male" value="male" <?php echo set_radio('sex', 'male', TRUE); ?> >
				  Male
				</label>
				<label class="radio inline">
				  <input type="radio" name="sex" class="sex" id="female" value="female" <?php echo set_radio('sex', 'female'); ?>  >
				  Female
				</label>
            </div>
          </div>
          <hr>
          <div class="control-group">
            <label class="control-label" for="institution">Home Institution *</label>
            <div class="controls">
				<select class="input input-xlarge" id="institution" name="institution" data-placeholder="Choose Institution">
					<option value=""></option>
				<?php foreach($institutions as $inst) 
				{ 
				echo "<option value='".$inst['id']."' " .set_select('institution', $inst['id']). ">".$inst['name']."</option>";			
				}?>
				</select>
              <span class="help-inline">
              	<button id="new_institution" role="button" style="cursor: pointer;" class="btn">Add New</button> 
              	<i id="institution_popover" class="icon-info-sign"></i>
              </span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="department">Department/School</label>
            <div class="controls">
				<select class="input input-xlarge" id="department" name="department">
					<option value="0">No Department/School</option>
				<?php if(isset($departments)){ foreach($departments as $department) 
				{ 
				echo "<option value='".$department['id']."' " .set_select('department', $department['id']). ">".$department['name']."</option>";			
				}}?>
				</select>
              <span class="help-inline">
              	<a type="btn" style="display:none" id="new_department_button" href="" role="button" data-toggle="modal" data-target="#new-department"  style="cursor: pointer;" class="btn">Add New</a> 
              </span>
            </div>
          </div>          
          <hr>
         <div class="control-group visiting_position_control_group">
            <label class="control-label" for="visiting_position">Visiting Position</label>
            <div class="controls">
            <input type="hidden" name="honorary" value="0" />
            <label class="checkbox"><input type="checkbox" name="honorary" id="honorary" value="1" <?php echo set_checkbox('honorary', '1'); ?>> Honorary</label>
            <select class="input input-xlarge" id="visiting_position" name="visiting_position">
				<?php foreach($visiting_positions as $position) 
				{ 
				echo "<option value='".$position['id']."' " .set_select('visiting_position', $position['id']). ">".$position['position_name']."</option>";			
				}?>
				</select>				
              <span class="help-inline"></span>
            </div>
          </div>           
          <div class="control-group">
            <label class="control-label" for="research_group">Research Group *</label>
            <div class="controls">
				<select class="input input-xlarge" id="research_group" name="research_group" data-placeholder="Choose Group">
					<option value=""></option>
				<?php foreach($research_groups as $group) 
				{ 
				echo "<option value='".$group['id']."' " .set_select('research_group', $group['id']). ">".$group['name']."</option>";			
				}?>
				</select>
              <span class="help-inline">
              	<a type="btn" id="new_research_group" href="" role="button" data-toggle="modal" data-target="#new-research-group" style="cursor: pointer;" class="btn">Add New</a>
              	<i id="research_popover" class="icon-info-sign"></i>
              </span>
            </div>
          </div> 
          <div class="control-group">
            <label class="control-label" for="host">Host *</label>
            <div class="controls">
				<select class="input input-xlarge" id="host" name="host" data-placeholder="Choose Host">
					<option value=""></option>
				<?php foreach($hosts as $host) 
				{ 
				echo "<option value='".$host['id']."' " .set_select('host', $host['id']). ">".$host['first_name']." ".$host['last_name']."</option>";			
				}?>
				</select>
              <span class="help-inline">
              	<a type="btn" id="new_host" href="" role="button" data-toggle="modal" data-target="#new-host" style="cursor: pointer;" class="btn">Add New</a>
              	<i id="host_popover" class="icon-info-sign"></i>
              </span>
            </div>
          </div>                                
          <div class="control-group">
            <label class="control-label" for="from_date">From Date *</label>
            <div class="controls">
              <input type="text" class="input input-small" placeholder="dd.mm.yyyy" id="datepicker_from" name="from_date" value="<?php echo set_value('from_date'); ?>" >
           	  <span class="help-inline"><small>Start date in dd.mm.yyyy format</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="to_date">To Date *</label>
            <div class="controls">
              <input type="text" class="input input-small" placeholder="dd.mm.yyyy" id="datepicker_to" name="to_date" value="<?php echo set_value('to_date'); ?>" >
           	  <span class="help-inline"><small>End date in dd.mm.yyyy format</small></span>
            </div>
          </div>                  
                                              
          <div class="form-actions">
          	<input type="hidden" name="returning_guest_id" id="returning_guest_id" value="<?php echo set_value('returning_guest_id');?>">
            <button type="submit" class="btn btn-success">Create new Visit</button>
            <a type="btn" href="<?php print site_url();?>" class="btn">Cancel</a>
          </div>
          
        </fieldset>
      </form>		
</div>		
</div>		
	</div>
		
	

