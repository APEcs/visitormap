<?php $this->load->helper('form'); ?>

	<div class="modal fade bigInstitutionModal" id="new-institution" tabindex="-1" role="dialog" aria-labelledby="new-institution-label" aria-hidden="true" style='display: none;' >

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>

		<div class="modal-body inst-modal-body">
			<div class="new-institution-container">
			  <?php
			  $attributes = array('class' => '', 'id' =>"new-institution-form");
			  echo form_open('institutions/new_institution', $attributes);
			  ?>
				<fieldset>
		          <legend class="new-institution-legend">Specify the location of the new institution</legend>

		          <div class="control-group">
		            <label class="control-label" for="institution_name">Institution Full Name</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="institution_name" name="institution_name" >
		           	  <button type="button" id="search" style="cursor: pointer;" onclick="searchInstitution()" class="btn btn-success">Search</button>
		            </div>
		          </div>
		      <div class="hide-initially" style="display: none;">
		          <span class="help-inline help-about-inst-map"><small>Search for the institution to find the location automatically. Right-click on the map to adjust the location.</small></span>
				  <div class="institution-map">
					<div id="map_canvas" style="width:100%; height:100%"></div>
				  </div>
				  <span class="help-inline help-about-inst-map after-map"><small>Right-click on the map to manually select the location</small></span>

		          <div class="control-group">
		            <label class="control-label" for="address1">Address</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="address1" placeholder="10 Downing Street" name="address1" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="address2">Address 2</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="address2" name="address2" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="postal_code">Postal code</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="postal_code" name="postal_code" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="city">Town/City</label>
		            <div class="controls">
		              <input type="text" class="input input-xlarge" id="city" name="city" >
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="countrySelector">Country</label>
		            <div class="controls">
						<select class="input" id="countrySelector" name="countrySelector">
							<option value="">Please Select</option>
						</select>
		              <span class="help-inline"><small></small></span>
		            </div>
		          </div>
		     </div>
		          <div class="new-institution-actions">
		          	<input type="hidden" id="lat" name="lat" value="">
		          	<input type="hidden" id="long" name="long" value="">
		          	<a id="inst-save" style="display: none;" type="btn" href="#" onclick="saveInstitution()" class="btn btn-success">Save Institution</a>
		            <a type="btn" href="#" data-dismiss="modal" aria-hidden="true" class="btn">Cancel</a>
		          </div>
				</form>


			  <?php echo "<p><small>". validation_errors(). "</small></p>";
			  if (isset($edit_visit_error)) echo "<p><small>". $edit_vist_error. "</small></p>";
			  ?>
			</div>
		</div>
	</div>


<script src="<?php print site_url();?>js/new.institution.js"></script>
