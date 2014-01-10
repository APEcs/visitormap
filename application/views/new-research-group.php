<?php $this->load->helper('form'); ?>

<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>

	<div class="modal fade bigModal" id="new-research-group" tabindex="-1" role="dialog" aria-labelledby="new-research-group-label" aria-hidden="true" style='display: none;' >
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>
		
		<div class="modal-body inst-modal-body">
			<div class="new-group-container">
			  <?php 
			  $attributes = array('class' => '', 'id' =>"new-research-group-form");
			  $hidden = array('group_id' => '');
			  echo form_open('research_groups/new_research_group', $attributes, $hidden);
			  ?>
				<fieldset>
		          <legend class="new-research-group-legend">Research Group details<small>(all fields are compulsory)</small></legend>

		          <div class="control-group">
		            <label class="control-label" for="group_acronym">Group Acronym</label>
		            <div class="controls">
		              <input type="text" class="input" id="group_acronym" name="group_acronym" placeholder="Acronym" >
		            </div>
		          </div>
				  <div class="control-group">
		            <label class="control-label" for="group_name">Group Full Name</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="group_name" placeholder="Name" name="group_name" >
		            </div>
		          </div>		             
		          <div class="change-pass-actions">
		          	<a type="btn" onclick="saveGroup()" class="btn btn-success">Save Group</a>
		            <a type="btn" href="#" onclick="cleanModalFields()" data-dismiss="modal" aria-hidden="true" class="btn">Cancel</a>
		          </div>
				</form>		
			  	
			 
			  <?php echo "<p><small>". validation_errors(). "</small></p>"; 
			  if (isset($edit_visit_error)) echo "<p><small>". $edit_vist_error. "</small></p>";
			  ?>
			</div>
		</div>
	</div>
	
<script src="<?php print site_url();?>js/new.research.group.js"></script>

