<?php
require_once 'system/templates/AbstractTemplate.class.php';
AbstractTemplate::initializeTemplate("myRivieraAdmin");

// LOAD THE VIEWs
if(!USER_CONNECTED) {
	
	// load the css
	echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/mobile/css/style.css" rel="stylesheet" />';
	require_once "system/templates/application/" . APPLICATION_NAME . '/views/login/mobile/Login.class.php';
	
	// BUILD THE VIEWs
	$login = new Login();
	$login->printTemplate();
	
} else { 
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/PublishView.class.php';
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();
	
	$publish = new PublishView();
	$publish->printTemplate();
}

?>
