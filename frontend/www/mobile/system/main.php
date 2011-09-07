<?php
	ob_start("ob_gzhandler");			// page compression
	@set_magic_quotes_runtime(false);	// for the magic_quotes
	
	// DEBUG
	ini_set('display_errors', 1);
	
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/templates/TemplateManager.class.php';
	require_once dirname(__FILE__).'/LoginHandler.class.php';
	
	session_start();
	
	// HANDLE LOGIN
	$handler = new LoginHandler();
	$handler->handleRequest();
	
	$templateManager = new TemplateManager();
	define('USER_CONNECTED', isset($_SESSION['user']) || isset($_GET['user']));
	if (USER_CONNECTED) {
		if(isset($_GET['application']) && $_GET['application'] != "0"){
			$templateManager->selectTemplate('application/'.$_GET['application']);
		} else if(isset($_GET['admin']) && $_GET['admin'] != "0"){
			$templateManager->selectTemplate('admin');
		} else {
			$templateManager->selectTemplate('home');
		}
	} else {
		$templateManager->selectTemplate('login');
	}
	$templateManager->callTemplate();
	
?>