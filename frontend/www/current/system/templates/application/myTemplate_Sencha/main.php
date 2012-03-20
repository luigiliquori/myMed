<?php

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myTemplate_Sencha");

	echo '<input type="hidden" name="application" id="applicationName" value="' . APPLICATION_NAME . '" />';
	
	// Sencha Touch
	echo '<link rel="stylesheet" href="/lib/sencha/resources/css/sencha-touch.css" type="text/css" />';
	echo '<script type="text/javascript" src="/lib/sencha/sencha-touch-all-debug.js"></script>';
	
	// Application
	echo '<link rel="stylesheet" href="system/templates/application/' . APPLICATION_NAME . '/css/example.css"  type="text/css"/>';
	echo '<script type="text/javascript" src="system/templates/application/' . APPLICATION_NAME . '/javascript/app.js"></script>';
	
?>
