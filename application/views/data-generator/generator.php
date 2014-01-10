<?php $this->load->helper('url'); ?>
<?php $this->load->helper('form'); ?>




	
	<div class="container">
	
	  <div class="row">
	    
	    <div class="span12">
		<h3>Generate Random Visits</h3>
		<?php
		$attributes = array('class'=> "form-horizontal form-generate", 'id' =>"form-generate");
	      	echo form_open('data-generator/generator/generate', $attributes); ?>

	      	  <div class="control-group">
			    <label class="control-label" for="qty">How many visits</label>
			    <div class="controls">
			      <input type="text" id="qty" name="qty" value="<?php echo set_value('qty'); ?>" placeholder="Qty">
			      <?php echo form_error('qty'); ?>
			    </div>
			  </div>
	      	  <div class="control-group">
			    <label class="control-label" for="from_date">Start date</label>
			    <div class="controls">
			      <input type="text" id="from_date" name="from_date" value="<?php echo set_value('from_date'); ?>" placeholder="dd.mm.yyyy">
			      <?php echo form_error('from_date'); ?>
			    </div>
			  </div>
	      	  <div class="control-group">
			    <label class="control-label" for="to_date">To date</label>
			    <div class="controls">
			      <input type="text" id="to_date" name="to_date" value="<?php echo set_value('to_date'); ?>"  placeholder="dd.mm.yyyy">
			      <?php echo form_error('to_date'); ?>
			    </div>
			  </div>
			  <div class="control-group">
			    <label class="control-label" for="min_stay">Min stay days</label>
			    <div class="controls">
			      <input type="text" id="min_stay" name="min_stay" value="<?php echo set_value('min_stay'); ?>" placeholder="No of days">
			      <?php echo form_error('min_stay'); ?>
			    </div>
			  </div>
			  <div class="control-group">
			    <label class="control-label" for="max_stay">Max stay days</label>
			    <div class="controls">
			      <input type="text" id="max_stay" name="max_stay" value="<?php echo set_value('max_stay'); ?>" placeholder="No of days">
			      <?php echo form_error('max_stay'); ?>
			    </div>
			  </div>				  			  			  
			  <div class="control-group">
			  	<div class="controls">
			  		<button type="submit" id="generate-btn" class="btn btn-small">Generate</button>
			  	</div>
			  </div>
			  		
			</form>		
			
		<?php 
		if (isset($save_success))
		{
			echo "<h5>New visits successfully entered!</h5>";
		}
		if (isset($new_visits)) 
      	{?>
      	<h3>New Randomly Generated Visits</h3>
      	<table class="table table-condensed table-bordered">
			
		  <thead>
		    <tr>
		      <th class="table-head">No</th>
		      <th class="table-head">Visitor</th>
		      <th class="table-head">Institution</th>
		      <th class="table-head">Group</th>
		      <th class="table-head">Host</th>
		      <th class="table-head">From Date</th>
		      <th class="table-head">To Date</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php 
		  	if(isset($new_visits))
		  	{
				$x=1;
				foreach ($new_visits as $visit)
				{
		  		?>
				  	<tr>
				  		<td>
				  		<small><?php echo $x;?></small>
				  		</td>
				  		<td>
				  		<small><?php echo $visit["visitor_first_name"]." ".$visit["visitor_last_name"];?></small>
				  		</td>
				  		<td>
				  		<small><?php echo $visit["institution"];?></small>
				  		</td>
				  		<td>
				  		<small><?php echo $visit["group"];?></small>
				  		</td> 			  		
				  		<td>
				  		<small><?php echo $visit["host_first_name"]." ".$visit["host_last_name"];?></small>
				  		</td>
				  		<td class="date-column">
				  		<small><?php echo $visit["from_date"];?></small>
				  		</td>  	
				  		<td class="date-column">
				  		<small><?php echo $visit["to_date"];?></small>
				  		</td> 			  					  		  				  		
				  	</tr>
		  		<?php 
				$x++;
				}
      		}
		  	?>
		  </tbody>
		  
		</table>
      	<a class="btn btn-primary" href="<?php print site_url();?>data-generator/generator/save">Save new visits</a>
      	<?php 
     
      	} ?>
      	<hr>
      	<?php 
      	
      	if (isset($visits)) 
      	{?>
      	<div class="row">
      		<div class="span6">
      		<h3>Visits in Database</h3>
      		</div>
      		<div class="span6 pull-right">
      		<br>
      		<a href="<?php print site_url();?>data-generator/generator/delete_all" class="btn btn-danger pull-right">Delete all Visitis</a>
      		</div>
      	</div>
      	
      	<table class="table table-condensed table-bordered">
		  <thead>
		    <tr>
		      <th class="table-head">No</th>
		      <th class="table-head">Visitor</th>
		      <th class="table-head">Institution</th>
		      <th class="table-head">Group</th>
		      <th class="table-head">Host</th>
		      <th class="table-head">From Date</th>
		      <th class="table-head">To Date</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php 
			$x=1;
			foreach ($visits as $visit)
			{
	  		?>
			  	<tr>
			  		<td>
			  		<small><?php echo $x;?></small>
			  		</td>
			  		<td>
			  		<small><?php echo $visit["visitor_first_name"]." ".$visit["visitor_last_name"];?></small>
			  		</td>
			  		<td>
			  		<small><?php echo $visit["institution"];?></small>
			  		</td>
			  		<td>
			  		<small><?php echo $visit["group"];?></small>
			  		</td> 			  		
			  		<td>
			  		<small><?php echo $visit["host_first_name"]." ".$visit["host_last_name"];?></small>
			  		</td>
			  		<td class="date-column">
			  		<small><?php echo $visit["from_date"];?></small>
			  		</td>  	
			  		<td class="date-column">
			  		<small><?php echo $visit["to_date"];?></small>
			  		</td> 			  					  		  				  		
			  	</tr>
	  		<?php 
			$x++;
			}
		  	?>
		  </tbody>
		</table>
      	
      	
      	<?php 
      	}
      	?>      	
	    </div>
	  
	  </div>
	
	</div>  
	  

	
	
