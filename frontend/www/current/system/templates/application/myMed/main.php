<?php

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myMed");
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/views/Home.class.php';
	require_once dirname(__FILE__).'/views/Profile.class.php';
	require_once dirname(__FILE__).'/views/login/LoginDesktop.class.php';
	require_once dirname(__FILE__).'/views/login/LoginMobile.class.php';
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// LOAD THE VIEWs
	if(USER_CONNECTED) {
		
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
		
		$home = new Home();
		$home->printTemplate();
		$profile = new Profile();
		$profile->printTemplate();
		include('views/dialog/updateProfile.php');
		
	} else if(TARGET == "mobile") {
		
		$login = new LoginMobile();
		$login->printTemplate();
		
	} else {
		
		$login = new LoginDesktop();
		$login->printTemplate();
		
	}

?>