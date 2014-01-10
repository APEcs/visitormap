<?php $this->load->helper('url'); ?>

	
<div class="container main_content">		
	<br>
	<div class="row">
		<div class="span12">
			<h1>Institutions <small>Browse and Manage</small></h1>
		<?php 
		  if(isset($update_inst_success)) 
		  { ?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Institution was successfully updated.
		 	</div>
		  <?php
		  } ?>
			<div class="alert alert-success new-inst-entered" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> New institution was saved.
		 	</div>
		 	<div class="alert alert-success inst-deleted" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Institution was deleted.
		 	</div>
		 	<div class="alert alert-success inst-updated" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Institution was updated.
		 	</div>					
		</div>
	
	</div>
		
	<div class="row">
		<div class="span12">
			<table id="tableToSort" class="table table-condensed table-bordered">
				<thead>
		    		<tr>
		      		<th class="table-head table-inst-name-column">Name</th>
		      		<th class="table-head table-adr-column">Address</th>
		      		<th class="table-head table-post-coulmn">Postal code</th>
		      		<th class="table-head table-city-column">Town/City</th>
		      		<th class="table-head table-country-column">Country</th>
		      		<th class="table-head table-dep-column">Departments</th>
		      		<th class="table-head table-visits-column">Visits</th>
		      		<th class="table-head table-edit-column">Edit &nbsp;&nbsp;&nbsp;&nbsp;</th>
		    		</tr>
		  		</thead>
		 		<tbody id="table">

		 		</tbody>
		 	</table>
		 	<div class="alert alert-success new-inst-entered" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> New institution was saved.
		 	</div>	
		 	<a class="btn" id="new_institution" onclick="loadMapsScript()" role="button" data-toggle="modal" data-target="#new-institution"  style="cursor: pointer;">Add New Institution</a>
		</div> <!-- end of span12 -->
	</div> <!-- end of row -->
</div>
<div id="confirmDiv" >
   
</div> 
	
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/existing.institutions.js"></script>
<script type="text/javascript">
	window.onload = initializeInstitutions;
</script>	
