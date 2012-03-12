<?php 
	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myApplicationBuilder");
	
	// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
	echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
	echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
	echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/tabbar/MainView.class.php';
	require_once dirname(__FILE__).'/views/tabbar/BuildView1.class.php';
	require_once dirname(__FILE__).'/views/tabbar/BuildView2.class.php';
	require_once dirname(__FILE__).'/views/tabbar/BuildView3.class.php';

	// IMPORT POPUP
	require_once dirname(__FILE__).'/views/popup/Option.class.php';
	require_once dirname(__FILE__).'/views/popup/Validate.class.php';
	
	// IMPORT THE RESULT VIEW
	require_once dirname(__FILE__).'/views/result/ResultView.class.php';
	require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	
	// IMPORT AND DEFINE THE REQUEST HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
	$appHandler = new MyApplicationHandler();
	$appHandler->handleRequest();
	require_once dirname(__FILE__).'/handler/BuilderHandler.class.php';
	$builder = new BuilderHandler();
	$builder->handleRequest();

	if(isset($_POST['method'])) { 				// Print The Results View
		if($_POST['method'] == 'getDetail') {
			$details = new DetailView($appHandler);
			$details->printTemplate();
		} else {
			$result = new ResultView($appHandler);
			$result->printTemplate();
		}
	} else {									// Print The Default Views
		$main = new MainView();
		$main->printTemplate();

		$build1 = new BuildView1();
		$build1->printTemplate();
		
		$build2 = new BuildView2();
		$build2->printTemplate();
		
		$build3 = new BuildView3();
		$build3->printTemplate();
		
		$option = new Option();
		$option->printTemplate();
		
		$validate = new Validate();
		$validate->printTemplate();
	}
?>
