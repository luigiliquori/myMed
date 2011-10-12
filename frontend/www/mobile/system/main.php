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
	$target = "mobile";
	if ($detect->isMobile()) {
		$target = "mobile";
	}
	
	$templateManager = new TemplateManager();
	define('USER_CONNECTED', isset($_SESSION['user']) || isset($_GET['user']));
	if (USER_CONNECTED) {
		if(isset($_GET['application']) && $_GET['application'] != "0"){
			$templateManager->selectTemplate('application/'.$_GET['application']);
		} else if(isset($_GET['admin']) && $_GET['admin'] != "0"){
			$templateManager->selectTemplate('admin');
		} else {
			$templateManager->selectTemplate($target . '/home');
		}
	} else {
		$templateManager->selectTemplate($target . '/login');
	}
	$templateManager->callTemplate();
	
?>