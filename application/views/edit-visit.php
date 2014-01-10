<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script src="<?php print site_url();?>js/new.visit.js"></script>
<script src="<?php print site_url();?>js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?php print site_url();?>js/select2.min.js"></script>
<script src="<?php print site_url();?>js/edit.visit.js"></script>
<script src="<?php print site_url();?>js/existing.research.groups.js"></script>
<script src="<?php print site_url();?>js/existing.hosts.js"></script>

<div class="container main-content">
	<hr>
<div class="row">
<div class="span12 ">
  <?php
  if(isset($update_visit_success) && $update_visit_success==FALSE)
  { ?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
 		<strong>Error!</strong> There were unexpected problems with saving the changes... Please try again.
 	</div>
  <?php
  }
  if(isset($update_person_success) && $update_person_success==FALSE)
    { ?>
  	<div class="alert alert-error">
  		<button type="button" class="close" data-dismiss="alert">×</button>
   		<strong>Error!</strong> There were unexpected problems with saving personal information... Please try again.
   	</div>
    <?php
    }
  $attributes = array('class'=> "form-horizontal well", 'id' => "edit_visit_form");
  $hidden = array('visitor_id' => $visit["visitor_id"], 'visit_id'=>$visit['id'], 'origin' => '');
  echo form_open('visits/do_edit_visit', $attributes, $hidden);
  ?>
        <fieldset>
          <legend>Edit existing visit <small>(fields with * are compulsory)</small></legend>
          <div id="name-warning" style="display: none" class="alert">
			  <strong>Notice!</strong> Visitor's title, name and sex will be updated across all visits. (provided, person has more than one visit)
		  </div>
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
				if($visit["visitor_id"]==$guest["id"])
					echo "<option value='".$guest['id']."' " .set_select('returning_guest', $guest['id'], TRUE). ">".$guest['first_name']." ".$guest['last_name']."</option>";
				else
					echo "<option value='".$guest['id']."' " .set_select('returning_guest', $guest['id']). ">".$guest['first_name']." ".$guest['last_name']."</option>";
				}?>
				</select>
              <span class="help-inline"><small>Search for existing guest</small></span>
            </div>
          </div>
		 <div class="control-group">
            <label class="control-label" for="title">Title *</label>
            <div class="controls">
              <input type="text" class="input input-small trigger-warning" placeholder="Dr/Mr/Ms/etc." id="title" name="title" value="<?php echo set_value('title', $visit['visitor_title']); ?>">
              <span class="help-inline"><small></small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="first_name">First Name *</label>
            <div class="controls">
              <input type="text" class="input trigger-warning" id="first_name" placeholder="John" name="first_name" value="<?php echo set_value('first_name', $visit['visitor_first_name']); ?>">
              <span class="help-inline"><small>Given Name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="last_name">Last Name *</label>
            <div class="controls">
              <input type="text" class="input trigger-warning" id="last_name" placeholder="Smith" name="last_name" value="<?php echo set_value('last_name', $visit['visitor_last_name']); ?>">
              <span class="help-inline"><small>Familiy name</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="public">Is visitor's name public?</label>
            <div class="controls">
				<label class="radio">
				  <input type="radio" name="public" id="public_yes" value="1"
				  	<?php
				  	if($visit['hide_name']==0) echo set_radio('public', '1', TRUE);
				  	else echo set_radio('public', '1');
				  	?> >
				  <b>Yes</b>, everybody can see visitor's name
				</label>
				<label class="radio">
				  <input type="radio" name="public" id="public_no" value="0"
				  	<?php
				  	if($visit['hide_name']==1) echo set_radio('public', '0', TRUE);
				  	else echo set_radio('public', '0');
				  	?> >
				  <b>No</b>, only authenticated users can see visitor's name
				</label>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="sex">Sex</label>
            <div class="controls">
				<label class="radio inline">
				  <input type="radio" name="sex" class="sex trigger-warning" id="male" value="male"
				  	<?php
				  	if($visit['visitor_sex']=='male') echo set_radio('sex', 'male', TRUE);
				  	else echo set_radio('sex', 'male');
				  	?> >
				  Male
				</label>
				<label class="radio inline">
				  <input type="radio" name="sex" class="sex trigger-warning" id="female" value="female"
				  	<?php
				  	if($visit['visitor_sex']=='female') echo set_radio('sex', 'female', TRUE);
				  	else echo set_radio('sex', 'female');
				  	?> >
				  Female
				</label>
            </div>
          </div>
          <hr>
          <div class="control-group"><a name="instdiv"></a>
            <label class="control-label" for="institution">Home Institution *</label>
            <div class="controls">
				<select class="input input-xlarge" id="institution" name="institution" data-placeholder="Choose Institution">
					<option value=""></option>
				<?php foreach($institutions as $inst)
				{
				if($visit['institution_id']==$inst['id'])
					echo "<option value='".$inst['id']."' " .set_select('institution', $inst['id'], TRUE). ">".$inst['name']."</option>";
				else
					echo "<option value='".$inst['id']."' " .set_select('institution', $inst['id']). ">".$inst['name']."</option>";
				}?>
				</select>
              <span class="help-inline">
              	<a type="btn" id="edit_institution" onclick="saveEdits()" href="<?php print site_url();?>institutions/edit_institution?id=<?php print $visit['institution_id']?>" role="button" style="cursor: pointer;" class="btn">Edit</a>
              	<a type="btn" id="new_institution" href="" role="button" onclick="loadMapsScript()" data-toggle="modal" data-target="#new-institution"  style="cursor: pointer;" class="btn">Add New</a>
              	<i id="institution_popover" class="icon-info-sign"></i>
              	<div id="institution_message" class="edit-message"></div>
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
				if($visit['department_id']==$department['id'])
					echo "<option value='".$department['id']."' " .set_select('department', $department['id'], TRUE). ">".$department['name']."</option>";
				else
					echo "<option value='".$department['id']."' " .set_select('department', $department['id']). ">".$department['name']."</option>";
				}}?>
				</select>
              <span class="help-inline">
              	<a type="btn" id="edit_department" href="<?php print site_url();?>departments/existing_departments?inst_id=<?php print $visit['institution_id']?>&dep_id=<?php print$visit['department_id'] ?>&origin=edit_visit&origin_id=<?php print $visit['id']?>" role="button" style="cursor: pointer;" class="btn">Edit</a>
              	<a type="btn" id="new_department_button" href="" role="button" data-toggle="modal" data-target="#new-department"  style="cursor: pointer;" class="btn">Add New</a>
              	<div id="department_message" class="edit-message"></div>
              </span>
            </div>
          </div>
          <hr>
         <div class="control-group visiting_position_control_group">
            <label class="control-label" for="visiting_position">Visiting Position</label>
            <div class="controls">
                        <input type="hidden" name="honorary" value="0" />
            <label class="checkbox"><input type="checkbox" name="honorary" id="honorary" value="1" <?php if($visit['honorary']==1) { echo set_checkbox('honorary', '1', TRUE);} else { echo set_checkbox('honorary', '1');} ?>> Honorary</label>
            <select class="input input-xlarge" id="visiting_position" name="visiting_position">
				<?php foreach($visiting_positions as $position)
				{
				if($visit['position_id']==$position['id'])
					echo "<option value='".$position['id']."' " .set_select('visiting_position', $position['id'], TRUE). ">".$position['position_name']."</option>";
				else
					echo "<option value='".$position['id']."' " .set_select('visiting_position', $position['id']). ">".$position['position_name']."</option>";
				}?>
				</select>
              <span class="help-inline"><small></small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="research_group">Research Group *</label>
            <div class="controls">
				<select class="input input-xlarge" id="research_group" name="research_group" data-placeholder="Choose Group">
					<option value=""></option>
				<?php foreach($research_groups as $group)
				{
				if($visit['group_id']==$group['id'])
					echo "<option value='".$group['id']."' " .set_select('research_group', $group['id'], TRUE). ">".$group['name']."</option>";
				else
					echo "<option value='".$group['id']."' " .set_select('research_group', $group['id']). ">".$group['name']."</option>";
				}?>
				</select>
              <span class="help-inline">
              	<a class='btn' type='button' id='editGroupBtn' href='<?php print $visit['group_id'] ?>'>Edit</a>
              	<a type="btn" id="new_research_group" href="" role="button" data-toggle="modal" data-target="#new-research-group" style="cursor: pointer;" class="btn">Add New</a>
              	<i id="research_popover" class="icon-info-sign"></i>
              	<div id="research_group_message" class="edit-message"></div>
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
				if($visit['host_id']==$host['id'])
					echo "<option value='".$host['id']."' " .set_select('host', $host['id'], TRUE). ">".$host['first_name']." ".$host['last_name']."</option>";
				else
					echo "<option value='".$host['id']."' " .set_select('host', $host['id']). ">".$host['first_name']." ".$host['last_name']."</option>";
				}?>
				</select>
              <span class="help-inline">
              	<a class='btn' type='button' id='editHostBtn' href='<?php print $visit['host_id'] ?>'>Edit</a>
              	<a type="btn" id="new_host" href="" role="button" data-toggle="modal" data-target="#new-host" style="cursor: pointer;" class="btn">Add New</a>
              	<i id="host_popover" class="icon-info-sign"></i>
              	<div id="host_message" class="edit-message"></div>
              </span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="from_date">From Date *</label>
            <div class="controls">
              <input type="text" class="input input-small" placeholder="dd.mm.yyyy" id="datepicker_from" name="from_date" value="<?php echo set_value('from_date', $visit['from_date']); ?>" >
           	  <span class="help-inline"><small>Start date in dd.mm.yyyy format</small></span>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="to_date">To Date *</label>
            <div class="controls">
              <input type="text" class="input input-small" placeholder="dd.mm.yyyy" id="datepicker_to" name="to_date" value="<?php echo set_value('to_date', $visit['to_date']); ?>" >
           	  <span class="help-inline"><small>End date in dd.mm.yyyy format</small></span>
            </div>
          </div>

          <div class="form-actions">
          	<input type="hidden" name="person_changed" id="person_changed" value="<?php echo set_value('person_changed', '0');?>">
            <input type="hidden" name="returning_guest_id" id="returning_guest_id" value="<?php echo set_value('returning_guest_id');?>">
            <button type="submit" class="btn btn-success">Save Changes</button>
            <a type="btn" href="javascript:history.go(-1);" class="btn">Cancel</a>
          </div>

        </fieldset>
      </form>
</div>
</div>
</div>
