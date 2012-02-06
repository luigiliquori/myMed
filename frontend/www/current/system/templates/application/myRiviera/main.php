<?php 
	define('APPLICATION_NAME', "myRiviera");
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/TripView.class.php';
	
	// IMPORT DIALOG
	require_once dirname(__FILE__).'/views/dialog/EditDialog.class.php';
	require_once dirname(__FILE__).'/views/dialog/OptionDialog.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	// VIEW
	$find = new FindView($handler);
	$find->printTemplate();
	
	$trip = new TripView();
	$trip->printTemplate();
	
	// DIALOG
	$edit = new EditDialog();
	$edit->printTemplate();
	
	$option = new OptionDialog();
	$option->printTemplate();
?>
