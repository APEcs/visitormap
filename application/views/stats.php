<?php $this->load->helper('url'); ?>

<div class="container-fluid main_content">

	<div class="row-fluid">
		<div class="span12 stats-span">
			<h4 class="stats-filter-header">Add or remove filters</h4>
			<div class="selector stats-selector">
				<select id="regionSelector" onchange="regionSelected()">
					<option value="all">All Regions</option>
				</select>
			</div>
			<div class="selector stats-selector">
				<select id="groupSelector" onchange="groupSelected()">
					<option value="all">All Groups</option>
				</select>
			</div>
		</div>			
	</div>
	<div class="row-fluid">		
		<div class="span4 stats-span center-align">
			<div class="stat-big-number" id="totalNo">xx</div>
			<h4>Total number of visits</h4>
		</div>
		<div class="span4 stats-span center-align" id="visits-per-year-span" rel="popover" data-original-title="Detailed statistics">
			<div class="stat-big-number" id="yearAvg">xx</div>
			<h4>The average of visits per year</h4>
		</div>
		<div class="span4 stats-span center-align">
			<div class="stat-big-number" id="lenAvg">xx days</div>
			<h4>Average visit length</h4>
		</div>		
	</div>
	<div class="row-fluid">
		<div class="span8 stats-span">
			<div id="line_chart_div"></div>
		</div>
		<div class="span4 stats-span">
			<div id="region_pie_div"></div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span5 stats-span">
			<div id="countries_pie_div"></div>
		</div>
		<div class="span7 stats-span">
			<div id="groups_pie_div"></div>
		</div>
	</div>	
</div>


	<div class="modal fade bigModal" id="visits-histogram-modal" tabindex="-1" role="dialog" aria-labelledby="visits-histogram-label" aria-hidden="true" style='display: none;' >
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>
		
		<div class="modal-body">
			<div class="new-group-container">
				<div id="visits_histogram_div"></div>
			</div>
		</div>
	</div>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="<?php print site_url();?>js/stats.js"></script>