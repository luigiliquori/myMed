<?php
	ob_start("ob_gzhandler");			// page compression
	@set_magic_quotes_runtime(false);	// for the magic_quotes
	
	// DEBUG
	ini_set('display_errors', 1);
	
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/templates/TemplateManager.class.php';
	require_once dirname(__FILE__).'/../contents/AbstractContent.class.php';
	
	session_start();

	$templateManager = new TemplateManager();
	define('USER_CONNECTED', isset($_SESSION['user']) || isset($_GET['user']));
	if (USER_CONNECTED) {
		$templateManager->selectTemplate('home');
	} else {
		$templateManager->selectTemplate('login');
	}
	$templateManager->callTemplate();
	
?>