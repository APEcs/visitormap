<?php $this->load->helper('url'); ?>
	
	 	<div class="navbar navbar-inverse  navbar-fixed-top">
		  <div class="navbar-inner">
		  	<div class="menu-container">

			    <ul class="nav">
			      <li class="active hover-shadows"><a href="<?php print site_url();?>" class="main-home-button">CS Research Visitors</a><a href="<?php print site_url();?>" class="alternative-home-button" style="display: none;">CS Visits</a></li>
			    
			    <?php if(isset($logged_in)) 
			    { ?>	
					
					<li class="dropdown">
                      <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Manage Users<b class="caret"></b></a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                        
                        <?php if($group=="admin" || $group == "director") {?>
                        	<li><a tabindex="-1" href="<?php print site_url();?>users/new_user">Create new user</a></li>
							<!-- <li><a tabindex="-1" href="<?php print site_url();?>users/user_list">Existing users</a></li> -->
                      	<?php } ?>
                      	
                      	<!-- <li><a tabindex="-1" href="<?php print site_url();?>users/edit_account">Edit your account</a></li> -->
                      </ul>
                    </li>
                    
                    <li class="dropdown">
                      <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Manage Visits<b class="caret"></b></a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                        
                        <?php if($group=="admin" || $group=="director") {?>
                        <li><a tabindex="-1" id="link-to-new-visit" href="<?php print site_url();?>visits/new_visit">Create new visit</a></li>
                        <li class="divider" ></li>
                      	<?php }?>
                      	<li><a id="link-to-existing-visits" tabindex="-1" href="<?php print site_url();?>visits/existing_visits">Visits</a></li>
                      	<?php if($group=="admin" || $group=="director") {?>
                      	<li class="divider" ></li>
                      	<li><a tabindex="-1" href="<?php print site_url();?>institutions/existing_institutions">Institutions</a></li>
                      	<li><a tabindex="-1" href="<?php print site_url();?>departments/existing_departments">Departments</a></li>
                      	<li class="divider" ></li>
                      	<li><a tabindex="-1" href="<?php print site_url();?>hosts/existing_hosts">Hosts</a></li>
                      	<li><a tabindex="-1" href="<?php print site_url();?>research_groups/existing_groups">Research Groups</a></li>
                      	<?php }?>
                      	
                      </ul>
                    </li>
			   <?php 
				} else { ?>
                  <li class="header-slogan hidden-phone">Research visitors to the School of Computer Science</li>
                <?php }?>
				  
			    </ul>
			    <ul class="nav pull-right">
			    
			    <?php if(isset($logged_in)) 
			    { ?>
			    
			    
			    <li class="pull-right active"><p class="hi-name"><?php echo "Hi ".$first_name;?>!</p></li>
			    <li class="pull-right active dropdown"> <a href="<?php print site_url();?>home/logout">Logout</a></li>
			    <?php 
				}		
				else 
				{ 
					if (uri_string() == "stats") 
					{
					?>
			    	<li class="active hover-shadows right-menu-adjust" ><input class="menu-bar-checkbox" id="mapCheckbox" type="checkbox"><a class="pull-right checkBoxButtons" role="button" href="<?php print site_url();?>" > Map</a></li>
			    	<li class="active hover-shadows right-menu-adjust"><input class="menu-bar-checkbox" id="tableCheckbox" type="checkbox"><a class="pull-right checkBoxButtons" role="button" href="<?php print site_url();?>" > Table</a></li>
			   		<li class="active hover-shadows right-menu-adjust"><input class="menu-bar-checkbox" id="statsCheckbox" type="checkbox" checked="checked"><a class="pull-right checkBoxButtons" role="button" href="<?php print site_url();?>" id="statsButton"> Stats</a></li>
					<?php
					}
					else 
					{ ?>
			    	<li class="active hover-shadows right-menu-adjust" ><input class="menu-bar-checkbox" id="mapCheckbox" type="checkbox" checked="checked"><a class="pull-right checkBoxButtons" role="button" href="#" id="mapButton"> Map</a></li>
			    	<li class="active hover-shadows right-menu-adjust"><input class="menu-bar-checkbox" id="tableCheckbox" type="checkbox" checked="checked"><a class="pull-right checkBoxButtons" role="button" href="#" id="tableButton"> Table</a></li>
			   		<li class="active hover-shadows right-menu-adjust"><input class="menu-bar-checkbox" id="statsCheckbox" type="checkbox"><a class="pull-right checkBoxButtons" role="button" href="<?php print site_url();?>stats" id="statsButton"> Stats</a></li>
					<?php
					}	
				} ?>
			    </ul>
		    </div>
		  </div>
		</div>