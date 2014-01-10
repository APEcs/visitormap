<?php $this->load->helper('url'); ?>
<?php $this->load->helper('form'); ?>

<script type="text/javascript">

<!-- ############ New User Form validation JavaScript ############## -->


$(document).ready(function(){

	  var $err_open = "<div class='text-error'><small>";
	  var $err_close = "</small></div>";
	  $("#inst").focus();

  $("#search-form").validate({
	  errorLabelContainer: "#search-error",
	  errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		    },

		rules: {
			inst: {
				required: true
			}

		},

		messages: {
			inst: {
		       required: $err_open + "Enter someting!" + $err_close
		     }
		 }
	});

});
<!-- ############ End of Form validation JavaScript ############## -->

</script>




	<div class="container">

	  <div class="row">

	    <div class="span12">
	    <h3>Search for institutions</h3>
    	<?php
      	$attributes = array('class'=> "form-search", 'id' =>"search-form");
      	echo form_open('data-generator/googleapi/search', $attributes); ?>

      	<div class="input-append">
		  <input class="span6 search-query" placeholder="Search" name="inst" id="inst" type="text" value="<?php if(isset($search)) echo $search; ?>">
		  <button class="btn" id="search-go" type="submit">Go!</button>
		</div>
      	</form>

      	<?php if (isset($search_results))
      	{?>
      	<table class="table table-condensed table-bordered">
			<h3>Search results</h3>
		  <thead>
		    <tr>
		      <th class="table-head">No</th>
		      <th class="table-head">Name</th>
		      <th class="table-head">Lat</th>
		      <th class="table-head">Long</th>
		      <th class="table-head">View Map</th>
		      <th class="table-head">Add to DB</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php
			$x=1;
			foreach ($search_results as $inst)
			{
	  		?>
			  	<tr>
			  		<td>
			  		<?php echo $x;?>
			  		</td>
			  		<td>
			  		<?php echo $inst["name"];?>
			  		</td>
			  		<td>
			  		<?php echo $inst["lat"];?>
			  		</td>
			  		<td>
			  		<?php echo $inst["long"];?>
			  		</td>
			  		<td>
			  		<a href="<?php echo $inst["maps_url"];?>" target="_blank" >Google Maps</a>
			  		</td>
			  		<td>
			  		<?php
			  		if ($inst["in_db"]==FALSE)
					{
					?>
			  		<a href="<?php echo site_url();?>data-generator/googleapi/save">Save</a>
			  		<?php
					}
					else
					{?>
					<p class="muted">Allready saved</p>
					<?php
					}?>
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
      	if (isset($search) && (!isset($search_results)))
      	{
      		echo "<h5>Couldn't find any results, sorry</h5>";
      	}
      	if (isset($save_success))
      	{
      		echo "<h5>New Institution successfully saved!</h5>";
      	}
      	?>

	    </div>

	    <div class="span12">
		  <table class="table table-condensed table-bordered">
			<h3>List of saved Institutions</h3>
		  <thead>
		    <tr>
		      <th class="table-head">No</th>
		      <th class="table-head">Name</th>
		      <th class="table-head">Lat</th>
		      <th class="table-head">Long</th>
		      <th class="table-head">View Map</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php
		  	if ($institutions != NULL)
			{
				$x=1;
				foreach ($institutions as $inst)
				{
		  	?>
				  	<tr>
				  		<td>
				  		<?php echo $x;?>
				  		</td>
				  		<td>
				  		<?php echo $inst["name"];?>
				  		</td>
				  		<td>
				  		<?php echo $inst["lat"];?>
				  		</td>
				  		<td>
				  		<?php echo $inst["long"];?>
				  		</td>
				  		<td>
				  		<a href="<?php echo $inst["maps_url"];?>" target="_blank">Google Maps</a>
				  		</td>
				  	</tr>
		  	<?php
				$x++;
				}
			}
		  	?>
		  </tbody>
		</table>

	    </div>

	  </div>

	</div>
