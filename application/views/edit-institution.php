<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script src="<?php print site_url();?>js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?php print site_url();?>js/select2.min.js"></script>
<script src="<?php print site_url();?>js/edit.institution.js"></script>

<div class="container main-content">
	<hr>
<div class="row">
<div class="span12 ">
<div class="well">
  <?php
  $attributes = array('class'=> "", 'id' => "edit_institution_form");
  $hidden = array('inst_id' => $inst["id"], 'lat' => $inst["lat"], 'long' => $inst["long"]);
  echo form_open('visits/do_edit_visit', $attributes, $hidden);
  ?>
        <fieldset>
          <legend class="new-institution-legend">Edit existing institution</legend>

          <div class="control-group">
            <label class="control-label" for="institution_name">Institution Full Name</label>
            <div class="controls">
              <input type="text" class="input input-xlarge" id="institution_name" name="institution_name" value="<?php echo set_value('institution_name', $inst['name']); ?>" >
           	  <button type="button" id="search" style="cursor: pointer;" onclick="searchInstitution()" class="btn">Search</button>
            </div>
          </div>

          <span class="help-inline help-about-inst-map"><small>Right-click on the map to adjust the location.</small></span>
		  <div class="institution-map">
			<div id="map_canvas" style="width:100%; height:100%"></div>
		  </div>
		  <span class="help-inline help-about-inst-map after-map"><small>Right-click on the map to manually select the location</small></span>

          <div class="control-group">
            <label class="control-label" for="address1">Address</label>
            <div class="controls">
              <input type="text" class="input input-xlarge" id="address1" value="<?php echo set_value('address1', $inst['address1']); ?>" name="address1" >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="address2">Address 2</label>
            <div class="controls">
              <input type="text" class="input input-xlarge" id="address2" name="address2" value="<?php echo set_value('address2', $inst['address2']); ?>" >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="postal_code">Postal code</label>
            <div class="controls">
              <input type="text" class="input input-xlarge" id="postal_code" name="postal_code" value="<?php echo set_value('postal_code', $inst['postal_code']); ?>" >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="city">Town/City</label>
            <div class="controls">
              <input type="text" class="input input-xlarge" id="city" name="city" value="<?php echo set_value('city', $inst['city']); ?>" >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="countrySelector">Country</label>
            <div class="controls">
				<select class="input" id="countrySelector" name="countrySelector">
					<?php foreach($countries as $country)
					{
					if($country["id"]==$inst["country_id"])
						echo "<option value='".$country['alpha_2']."' " .set_select('returning_guest', $country['alpha_2'], TRUE). ">".$country['name']."</option>";
					else
						echo "<option value='".$country['alpha_2']."' " .set_select('returning_guest', $country['alpha_2']). ">".$country['name']."</option>";
					}?>
				</select>
              <span class="help-inline"><small></small></span>
            </div>
          </div>
          <div class="new-institution-actions">
          	<a type="btn" onclick="saveInstitution()" class="btn btn-success">Save Institution</a>
            <a type="btn" onclick="cancelButtonClick()" class="btn">Cancel</a>
          </div>
          </fieldset>
		</form>
</div>	<!-- end of well -->
</div>	<!-- end of span12 -->
</div>	<!-- end of row -->
</div>
