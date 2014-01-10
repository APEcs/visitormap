<?php $this->load->helper('url'); ?>


<div class="container main_content">
	<br>
	<div class="row">
		<div class="span12">
			<h1>
				Visits <small>Browse and Manage</small>
			</h1>
			<?php 
		  if(isset($update_visit_success)) 
		  { ?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Success!</strong> Visit was successfully updated.
			</div>
			<?php
		  } ?>
		  	<div class="alert alert-success" id="delete-success" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">×</button>
 				<strong>Success!</strong> Visit was deleted.
 			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div id="slider"></div>

			<div class="selector">
				<select id="regionSelector" onchange="regionSelected()">
					<option value="all">All Regions</option>
				</select>
			</div>
			<div class="selector">
				<select id="groupSelector" onchange="groupSelected()">
					<option value="all">All Groups</option>
				</select>
			</div>
			<div class="selector">
				<select id="hostSelector" onchange="hostSelected()">
					<option value="all">All Hosts</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<table id="tableToSort" class="table table-condensed table-bordered">
				<thead>
					<tr>
						<th class="table-head table-name-column">Visitor</th>
						<th class="table-head">Country</th>
						<th class="table-head">Institution</th>
						<th class="table-head">Group</th>
						<th class="table-head">Host</th>
						<th class="table-head">Visit Start</th>
						<th class="table-head">Visit End &nbsp;&nbsp;</th>
						<th id="edit-visit-table-head" class="table-head">Edit
							&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>
				<tbody id="table">

				</tbody>
			</table>
			<a class="btn" href="<?php print site_url();?>visits/new_visit">Create
				New Visit</a>
		</div>
		<!-- end of span12 -->
	</div>
	<!-- end of row -->
</div>
<div id="confirmDiv" >
   
</div> 


<script type="text/javascript"
	src="<?php print site_url();?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript"
	src="<?php print site_url();?>js/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript"
	src="<?php print site_url();?>js/existing.visits.js"></script>
<script type="text/javascript">
	window.onload = initializeVisits;
</script>
