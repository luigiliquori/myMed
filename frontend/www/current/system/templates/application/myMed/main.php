<?php

	require_once 'system/templates/AbstractTemplate.class.php';
	AbstractTemplate::initializeTemplate("myMed");
	
	// LOAD THE VIEWs
	if(USER_CONNECTED) { // HOME PAGE OF THE APPLICATION ---------------------------
		
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/Home.class.php';
		require_once dirname(__FILE__).'/views/home/Profile.class.php';
		
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
		
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}

?>