<?php $this->load->helper('form'); ?>

	<div class="modal fade bigModal" id="new-host" tabindex="-1" role="dialog" aria-labelledby="new-host-label" aria-hidden="true" style='display: none;' >

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>

		<div class="modal-body inst-modal-body">
			<div class="new-group-container">
			  <?php
			  $attributes = array('class' => '', 'id' =>"new-host-form");
			  $hidden = array('host_id' => '');
			  echo form_open('hosts/new_host', $attributes, $hidden);
			  ?>
				<fieldset>
		          <legend class="new-host-legend">Host details<small> (all fields are compulsory)</small></legend>

		          <div class="control-group">
		            <label class="control-label" for="host_title">Title</label>
		            <div class="controls">
		              <input type="text" class="input" id="host_title" name="host_title" placeholder="Dr/Mr/Ms/etc." >
		            </div>
		          </div>
				  <div class="control-group">
		            <label class="control-label" for="host_first_name">First Name</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="host_first_name" placeholder="Dane" name="host_first_name" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="host_last_name">Last Name</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="host_last_name" placeholder="Smith" name="host_last_name" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="host_sex">Sex</label>
		            <div class="controls">
						<label class="radio inline">
						  <input type="radio" name="host_sex" id="host_male" value="male" <?php echo set_radio('host_sex', 'male', TRUE); ?> >
						  Male
						</label>
						<label class="radio inline">
						  <input type="radio" name="host_sex" id="host_female" value="female" <?php echo set_radio('host_sex', 'female'); ?>  >
						  Female
						</label>
		            </div>
		          </div>
		          <div class="change-pass-actions">
		          	<a type="btn" onclick="saveHost()" class="btn btn-success">Save Host</a>
		            <a type="btn" href="#" onclick="cleanModalFields()" data-dismiss="modal" aria-hidden="true" class="btn">Cancel</a>
		          </div>
				</form>
			</div>
		</div>
	</div>

<script src="<?php print site_url();?>js/new.host.js"></script>
