<?php $this->load->helper('url'); ?>
<?php $this->load->helper('form'); ?>
<script type="text/javascript" src="<?php print site_url();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/select2.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/existing.departments.js"></script>    
    	
<div class="container main_content">		
	<br>
	<div class="row">
		<div class="span12">
			<h1>Departments <small>Browse and Manage</small></h1>
			<hr>
		 	<div class="alert alert-success departments-saved" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Changes were saved.
		 	</div>	
				
		</div>
	
	</div>
		
	<div class="row">
		<div class="span12">
		  <?php
		  $attributes = array('class'=> "", 'id' => "departments-form");
		  echo form_open('visits/do_new_visit', $attributes);
		  ?>
			<fieldset>
			  <div class="control-group">
	            <label class="control-label" for="institution_name">Choose Institution</label>
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
		              </span>
		            </div>
	          </div>
			<hr>
			<div id="dynamic-content" style="display: none;">
				Current departments
				<div id="departments">
				</div>
				<a class="btn" id="add-department-btn" onclick="addDepartmentField()">Add</a>
			</div> <!-- end of dynamic-content -->
			</fieldset>
			</form>
			
			<hr>
		 	<a class="btn btn-success" id="save-changes" onclick="saveChanges()" role="button" style="cursor: pointer;">Save</a>
		 	<a class="btn" id="cancel-changes" onclick="cancelChanges()" role="button" style="cursor: pointer;">Cancel</a>
		</div> <!-- end of span12 -->
	</div> <!-- end of row -->
</div>
<div id="confirmDiv" >
   
</div> 
	


<script type="text/javascript">
	//window.onload = initializeHosts;
</script>	
