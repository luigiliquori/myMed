<?php 
	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myTemplate");
	
	// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
	echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
	echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// IMPORT CONFIG
	require_once dirname(__FILE__).'/myConfig.php';
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/View1.class.php';
	require_once dirname(__FILE__).'/views/tabbar/View2.class.php';
	require_once dirname(__FILE__).'/views/tabbar/View3.class.php';

	// IMPORT THE RESULT VIEW
	require_once dirname(__FILE__).'/views/result/ResultView.class.php';
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$handler = new MyApplicationHandler();
	$handler->handleRequest();

	// DEFINE VIEWs
	if(isset($_POST['method'])) { 			
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($handler);
			$details->printTemplate();
		} else {
			$result = new ResultView($handler);
			$result->printTemplate();
		}
	} else {									
		$view1 = new View1();
		$view1->printTemplate();
		
		$view2 = new View2();
		$view2->printTemplate();
		
		$view3 = new View3();
		$view3->printTemplate();
		
	}
?>
