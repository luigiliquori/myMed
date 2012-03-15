<?php 

	// IMPORT CONFIG
	require_once dirname(__FILE__).'/myConfig.php';
	
	// LOAD THE UI FRAMEWORK
	echo '<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />';
	echo '<link rel="stylesheet" href="lib/jquery/jquery.mobile.actionsheet.css" />';
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>';
	echo '<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>';
	echo '<script type="text/javascript" src="lib/jquery/jquery.mobile.actionsheet.js"></script>';
	echo '<script src="lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>';
	echo '<link href="lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />';
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// LOAD THE VIEWs
	if(USER_CONNECTED) {
		// HOME PAGE OF THE APPLICATION ---------------------------
	
		// IMPORT THE HANDLER
		require_once dirname(__FILE__).'/handler/MenuHandler.class.php';
		$menuHandler = new MenuHandler();
		$menuHandler->handleRequest();
		require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';
		$updateHandler = new UpdateProfileHandler();
		$updateHandler->handleRequest();
		require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';
		$handler = new MyApplicationHandler();
		$handler->handleRequest();
	
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/View1.class.php';
		require_once dirname(__FILE__).'/views/home/View3.class.php';
		require_once dirname(__FILE__).'/views/result/ResultView.class.php';
		require_once dirname(__FILE__).'/views/result/DetailView.class.php';
	
		// DISCONNECT FORM
		echo '<form action="?application=' . APPLICATION_NAME . '" method="post" name="disconnectForm" id="disconnectForm">';
		echo '<input type="hidden" name="disconnect" value="1" /></form>';
	
		// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
		echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
		echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
		echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
		// BUILD THE VIEWs
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
			
			$view3 = new View3();
			$view3->printTemplate();
			
		}
	
	} else { // LOGIN PAGE OF THE APPLICATION ---------------------------
	
		// IMPORT THE MAIN VIEW
		if(TARGET == "mobile") {
			// load the css
			echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/mobile/css/style.css" rel="stylesheet" />';
			require_once dirname(__FILE__).'/views/login/mobile/Login.class.php';
		} else {
			// load the css
			echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/desktop/css/style.css" rel="stylesheet" />';
			require_once dirname(__FILE__).'/views/login/desktop/Login.class.php';
		}
		
		// BUILD THE VIEWs
		$login = new Login();
		$login->printTemplate();
		
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}
?>
