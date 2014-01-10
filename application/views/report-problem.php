<?php $this->load->helper('form'); ?>

<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>

	<div class="modal fade bigModal" id="report-problem" tabindex="-1" role="dialog" aria-labelledby="report-problem-label" aria-hidden="true" style='display: none;' >
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		</div>
		
		<div class="modal-body report-problem-body">
			<div class="new-group-container">
			  <?php 
			  $attributes = array('class' => '', 'id' =>"report-problem-form");
			  $hidden = array('visit_id' => '');
			  echo form_open('visits/report_problem', $attributes, $hidden);
			  ?>
				<fieldset>
		          <legend class="report-problem-legend">Visit Details</legend>

		          <div class="control-group">
					<div class="right-content">
						<p class="visit-details" id="prob-institution"></p>
						<p class="visit-details" id="prob-department"></p>
						<p class="visit-details list-divider" id="prob-country"></p>
						<p class="visit-details" id="prob-visitor"></p>
						<p class="visit-details" id="prob-visitor-gender"></p>
						<p class="visit-details list-divider" id="prob-visiting-position"></p>
						<p class="visit-details" id="prob-host"></p>
						<p class="visit-details" id="prob-group"></p>
						
						<p class="visit-details" id="prob-from-date"></p>
						<p class="visit-details list-divider" id="prob-to-date"></p>
					</div>
					<div class="left-content">
						<p class="visit-details"><b>Institution:</b></p>
						<p class="visit-details"><b>Department/School:</b></p>
						<p class="visit-details list-divider"><b>Country:</b></p>
						<p class="visit-details"><b>Visitor:</b></p>
						<p class="visit-details"><b>Gender:</b></p>
						<p class="visit-details list-divider"><b>Visiting Position:</b></p>
						<p class="visit-details"><b>Host:</b></p>
						<p class="visit-details"><b>Group:</b></p>
						<p class="visit-details"><b>From:</b></p>
						<p class="visit-details list-divider"><b>To:</b></p>
					</div>
					<div class="report-button">
						<p class="porblem-question">Is there a problem with this information?</p><a href="#" id="report-open" type="btn" class="btn btn-problem-action btn-link">Report it.</a>
					</div>
					<div class="report" style="display: none;">
						<p><b>Report a problem</b></p>
						<textarea name="problem-report-text" id="problem-report-text" placeholder="Describe what is wrong with this visit (required)"></textarea>
						<input type="text" id="problem-email" name="problem-email" placeholder="Your e-mail (required)">
					</div>
		          </div>
		          					
					
		          			             
		          <div class="change-pass-actions">
		          	<a type="btn" id="send-report-btn" style="display: none;" onclick="sendReport()" class="btn btn-success">Send Report</a>
		            <a type="btn" href="#" data-dismiss="modal" aria-hidden="true" class="btn">Close</a>
		          </div>
				</form>		
			</div>
		</div>
	</div>
	
<script src="<?php print site_url();?>js/report.problem.js"></script>

