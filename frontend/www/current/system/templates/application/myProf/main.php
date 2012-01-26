<?php 
	define('APPLICATION_NAME', "myApplicationName");
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/MyApplication.class.php';
	
	$app = new MyApplication("myApplicationName", "myApplicationName");
	$app->printTemplate();
?>

