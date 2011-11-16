<?php
	ob_start("ob_gzhandler");			// page compression
	@set_magic_quotes_runtime(false);	// for the magic_quotes
	
	// DEBUG
	ini_set('display_errors', 0);
	
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/templates/TemplateManager.class.php';
	require_once dirname(__FILE__).'/LoginHandler.class.php';
	
	session_start();
	
	// HANDLE LOGIN
	$handler = new LoginHandler();
	$handler->handleRequest();
	
	// MOBILE DETECT
	require_once 'php-mobile-detect/Mobile_Detect.php';
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		define('TARGET', "mobile");
	} else {
		define('TARGET', "desktop");
	}
	
	$templateManager = new TemplateManager();
	define('USER_CONNECTED', isset($_SESSION['user']));
	if (USER_CONNECTED) {
		
		// Try to get th position && Store the position of the user: TODO move this part
		if(isset($_GET["latitude"]) && isset($_GET["longitude"])) {
			$request = new Request("DHTRequestHandler", CREATE);
			$request->addArgument("key", $_SESSION['user']->name . "latitude");
			$request->addArgument("value", $_GET["latitude"]);
			$request->send();
			$request->addArgument("key", $_SESSION['user']->name . "longitude");
			$request->addArgument("value", $_GET["longitude"]);
			$request->send();
		}
		
		if(isset($_GET['application']) && $_GET['application'] != "0"){
			$templateManager->selectTemplate('application/'.$_GET['application']);
		} else if(isset($_GET['admin']) && $_GET['admin'] != "0"){
			$templateManager->selectTemplate('admin');
		} else {
			$templateManager->selectTemplate(TARGET . '/home');
		}
	} else {
		$templateManager->selectTemplate(TARGET . '/login');
	}
	$templateManager->callTemplate();
	
?>

