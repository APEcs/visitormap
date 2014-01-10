<?php $this->load->helper('url'); ?>

	
<div class="container main_content">		
	<br>
	<div class="row">
		<div class="span12">
			<h1>Research Groups <small>Browse and Manage</small></h1>
		 	<div class="alert alert-success group-deleted" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Group was deleted.
		 	</div>
		 	<div class="alert alert-success group-updated" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> Group was updated.
		 	</div>					
		</div>
	
	</div>
		
	<div class="row">
		<div class="span12">
			<table id="tableToSort" class="table table-condensed table-bordered">
				<thead>
		    		<tr>
		    		<th class="table-head table-group-acrony-col">Acronym</th>
		      		<th class="table-head table-group-name-col">Name</th>
		      		<th class="table-head table-host-unique-no-column">Unique visitors</th>
		      		<th class="table-head table-host-no-column">Visits</th>
		      		<th class="table-head table-edit-column">Edit &nbsp;&nbsp;&nbsp;&nbsp;</th>
		    		</tr>
		  		</thead>
		 		<tbody id="table">

		 		</tbody>
		 	</table>
		 	<div class="alert alert-success new-group-entered" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
		 		<strong>Success!</strong> New group was saved.
		 	</div>	
		 	<a class="btn" id="new_group" role="button" data-toggle="modal" data-target="#new-research-group"  style="cursor: pointer;">Add New Group</a>
		</div> <!-- end of span12 -->
	</div> <!-- end of row -->
</div>
<div id="confirmDiv" >
   
</div> 
	
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/existing.research.groups.js"></script>
<script type="text/javascript">
	window.onload = initializeGroups;
</script>	
