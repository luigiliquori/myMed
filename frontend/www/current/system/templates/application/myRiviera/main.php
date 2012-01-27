<?php 
	define('APPLICATION_NAME', "myRiviera");
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/TripView.class.php';
	
	// IMPORT DIALOG
	require_once dirname(__FILE__).'/views/dialog/EditDialog.class.php';
	
	// IMPORT THE RESULT VIEW
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	if(isset($_POST['method'])) { 				// Print The Results View
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($handler);
			$details->printTemplate();
		}
	} else {
		$find = new FindView();
		$find->printTemplate();
		
		$trip = new TripView();
		$trip->printTemplate();
		
		$edit = new EditDialog();
		$edit->printTemplate();
	}
?>
