<?php $this->load->helper('url'); ?>

	  <div class="subnav subnav-fixed">
				
			    <ul class="nav nav-pills">
			 	  <li><h4 class="hi-name">Data Generator &nbsp; </h4></li>
			 	  <li><a href="<?php print site_url();?>data-generator">Home</a></li>
			      <li><a href="<?php print site_url();?>data-generator/generator">Visits</a></li>
			      <li><a href="<?php print site_url();?>data-generator/googleapi">Institutions</a></li>
			      
			      <li class="pull-right"> <a href="<?php print site_url();?>data-generator/home/logout">Logout</a></li>
			      <li class="pull-right"><p class="hi-name"><small>Hi <?php echo $first_name;?>! &nbsp;</small></p></li>
			      
			    </ul>

			    

	  </div>