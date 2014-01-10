<?php $this->load->helper('url'); ?>
<div id="institution_message"></div>
<div class="home-map">
	<div id="map_canvas" style="width:100%; height:100%"></div>
</div>
	
<div class="container-fluid main_content">		
	<!--  
	<hr>	
	<div class="row">
		<div class="span12 home-map">
			
		</div>	
	</div>
	-->
	<div class="row-fluid">	
		<div class="span12 stats-span home-filters">
			<h5 class="filter_headline">Select time range, add filters and discover more</h5>
			<br>
			<div id="slider"></div>
			<div class="selector_container">
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
				<a href="<?php print site_url();?>stats" title="Link to detailed statistics about research visits" class="btn btn-info more-stats-btn">Statistics!</a>
			</div>
		</div>	
	</div>
	<br>
	<div id="mainTable">
		<div class="row-fluid">
			<div class="span12">
				<table id="tableToSort" class="table table-condensed table-bordered">
					<thead>
			    		<tr>
			      		<th data-placeholder="Search" class="table-head table-name-column">Visitor</th>
			      		<th data-placeholder="Search" class="table-head country-column">Country</th>
			      		<th data-placeholder="Search" class="table-head inst-column">Institution</th>
			      		<th data-placeholder="Search" class="table-head group-column">Group</th>
			      		<th data-placeholder="Search" class="table-head host-column">Host</th>
			      		<th data-placeholder="Search" class="table-head from-column">Visit Start</th>
			      		<th data-placeholder="Search" class="table-head to-column">Visit End &nbsp;&nbsp;</th>
			    		</tr>
			  		</thead>
			 		<tbody id="table">
	
			 		</tbody>
			 	</table>
			</div>
		</div>
	</div> 
</div>


	
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/markerclusterer.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAsKbnxVIdGUz_eNxKnZpID2JIrL-VAxs&sensor=false"></script>
<script type="text/javascript" src="<?php print site_url();?>js/markerwithlabel.min.js"></script>
<script type="text/javascript" src="<?php print site_url();?>js/visits.js"></script>
