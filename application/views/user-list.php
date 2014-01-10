<?php $this->load->helper('form'); ?>
<?php $this->load->helper('url'); ?>

<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<script src="<?php print site_url();?>js/user-form-validation.js"></script>
<script src="<?php print site_url();?>js/pGenerator.jquery.js"></script>

<div class="container main-content">	
	<hr>
	<div class="row">
		<div class="span">
			<br>
			
			<?php if(isset($new_user)) echo "A new user was successfully created for <b>". $new_user; ?>
		</div>
	</div>
	
</div>		
		
	</div>
		
	

