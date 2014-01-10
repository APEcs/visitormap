<?php $this->load->helper('url'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<title>CS Research Visitors Map</title>
	
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="http://cloud.github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
	<link href="<?php print site_url();?>css/style.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/style-responsive.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/sticky-footer.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/classic-slider.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/theme.table.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/jquery-ui-1.10.2.custom.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/select2.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/bootstrap-modal.css" rel="stylesheet" media="screen">
	<link href="<?php print site_url();?>css/style-adjustments.css" rel="stylesheet" media="screen">
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?php print site_url();?>js/jquery.cookie.min.js"></script>
	<script src="<?php print site_url();?>js/scripts.min.js"></script>
	<script src="<?php print site_url();?>js/date.format.js"></script>
	<script src="<?php print site_url();?>js/slider/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="<?php print site_url();?>js/slider/jQDateRangeSlider-min.js"></script>
    <script src="<?php print site_url();?>js/geturlvars.js"></script>
    <script type="text/javascript" src="<?php print site_url();?>js/bootstrap-modalmanager.js"></script>
	<script type="text/javascript" src="<?php print site_url();?>js/bootstrap-modal.js"></script>
	<script type="text/javascript" src="<?php print site_url();?>js/respond.min.js"></script>
	<script type="text/javascript">
		var full_url = window.location.href;
		var url_segments = full_url.split("/");
		var SITE_URL = "http://"+url_segments[2]+"/"+url_segments[3]+"/";
	</script>
	</head>
	
	<body class="preview" data-spy="scroll" data-target=".subnav" data-offset="80">
	<div class="page-container">
	<div id="wrap">