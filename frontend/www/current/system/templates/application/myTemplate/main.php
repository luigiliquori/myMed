<?php 
	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myTemplate");
	
	// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
	echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
	echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/PublishView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/SubscribeView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/FindView.class.php';
	
	// IMPORT THE RESULT VIEW
	require_once dirname(__FILE__).'/views/result/ResultView.class.php';
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	if(isset($_POST['method'])) { 				// Print The Results View
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($handler);
			$details->printTemplate();
		} else {
			$result = new ResultView($handler);
			$result->printTemplate();
		}
	} else {									// Print The Default Views
		$publish = new PublishView();
		$publish->printTemplate();
		
		$subscribe = new SubscribeView();
		$subscribe->printTemplate();
		
		$find = new FindView();
		$find->printTemplate();
	}
?>
