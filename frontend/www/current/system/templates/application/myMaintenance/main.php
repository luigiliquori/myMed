<?php

	require_once 'system/templates/AbstractTemplate.class.php';
	AbstractTemplate::initializeTemplate("myRiviera");
	
	echo '<link href="system/templates/application/myMaintenance/views/login/mobile/css/style.css" rel="stylesheet" />';
	require_once dirname(__FILE__).'/views/login/mobile/Login.class.php';
		
	// BUILD THE VIEWs
	$login = new Login();
	$login->printTemplate();

?>