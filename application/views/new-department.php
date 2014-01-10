<?php $this->load->helper('form'); ?>



	<div class="modal fade bigModal" id="new-department" tabindex="-1" role="dialog" aria-labelledby="new-department-label" aria-hidden="true" style='display: none;' >
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>
		
		<div class="modal-body inst-modal-body">
			<div class="new-group-container">
			  <?php 
			  $attributes = array('class' => '', 'id' =>"new-department-form");
			  echo form_open('departments/new_department', $attributes);
			  ?>
				<fieldset>
		          <legend class="new-research-group-legend">Add new Department/School <small>(all fields are compulsory)</small></legend>

		          <div class="control-group">
		            <label class="control-label" for="inst_name_for_new_dep">Institution</label>
		            <div class="controls">
		              <input type="text" class="input" disabled id="inst_name_for_new_dep" name="inst_name_for_new_dep">
		            </div>
		          </div>
				  <div class="control-group">
		            <label class="control-label" for="department_name">Department/School Name</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="department_name" placeholder="Name" name="department_name" >
		            </div>
		          </div>		             
		          <div class="change-pass-actions">
		          	<a type="btn" onclick="saveDepartment()" class="btn btn-success">Save Department</a>
		            <a type="btn" href="#" data-dismiss="modal" aria-hidden="true" class="btn">Cancel</a>
		          </div>
				</form>
			</div>
		</div>
	</div>
	
<script src="<?php print site_url();?>js/new.department.js"></script>

