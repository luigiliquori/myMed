<?php

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myTemplate");
	
	if(true || USER_CONNECTED) {

		echo '<input type="hidden" name="application" id="applicationName" value="' . APPLICATION_NAME . '" />';
		
		// Sencha Touch
		echo '<link rel="stylesheet" href="/lib/sencha/resources/css/sencha-touch.css" type="text/css" />';
		echo '<script type="text/javascript" src="/lib/sencha/sencha-touch-all-debug.js"></script>';
		
		// Application
		echo '<link rel="stylesheet" href="system/templates/application/' . APPLICATION_NAME . '_Sencha/css/example.css"  type="text/css"/>';
		echo '<script type="text/javascript" src="system/templates/application/' . APPLICATION_NAME . '_Sencha/app.js"></script>';
	
		// Google Maps
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
		
		
	}else{
		header("Refresh:0;url=http://" . $_SERVER['SERVER_NAME'] . "?application=0");
	}
		
		
?>
