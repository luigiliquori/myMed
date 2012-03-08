<?php

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myMed");
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// LOAD THE VIEWs
	if(USER_CONNECTED) { // HOME PAGE OF THE APPLICATION ---------------------------
		
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/Home.class.php';
		require_once dirname(__FILE__).'/views/home/Profile.class.php';
		
		// DISCONNECT FORM
		require_once dirname(__FILE__).'/handler/MenuHandler.class.php';
		$menuHandler = new MenuHandler();
		$menuHandler->handleRequest();
		echo '<form action="?application=' . APPLICATION_NAME . '" method="post" name="disconnectForm" id="disconnectForm">';
		echo '<input type="hidden" name="disconnect" value="1" /></form>';
		
		// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
		echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
		echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
		echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
		
		// BUILD THE VIEWs
		$home = new Home();
		$home->printTemplate();
		$profile = new Profile();
		$profile->printTemplate();
		include('views/dialog/updateProfile.php');
		
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
	}

?>