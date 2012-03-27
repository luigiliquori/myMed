<?php 

	require_once 'system/templates/AbstractTemplate.class.php';
	AbstractTemplate::initializeTemplate("myRiviera");

	// LOAD THE VIEWs
	if(USER_CONNECTED) { // HOME PAGE OF THE APPLICATION ---------------------------
		
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/MapView.class.php';
		require_once dirname(__FILE__).'/views/home/OptionView.class.php';
		require_once dirname(__FILE__).'/views/home/SearchView.class.php';
		require_once dirname(__FILE__).'/views/home/DetailsView.class.php';
		

		// BUILD THE VIEWs
		$map = new MapView();
		$map->printTemplate();
		$option = new OptionView();
		$option->printTemplate();
		$search = new SearchView();
		$search->printTemplate();
		$details = new DetailsView();
		$details->printTemplate();
		
		// PROFILE UPDATE
		include('views/dialog/updateProfile.php');
	
	} else { // LOGIN PAGE OF THE APPLICATION ---------------------------
	
		// IMPORT THE LOGIN VIEW
		if(TARGET == "mobile") {
			// load the css
			echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/mobile/css/style.css" rel="stylesheet" />';
			require_once "system/templates/application/" . APPLICATION_NAME . '/views/login/mobile/Login.class.php';
		} else {
			// load the css
			echo '<link href="system/templates/application/' . APPLICATION_NAME . '/views/login/desktop/css/style.css" rel="stylesheet" />';
			require_once "system/templates/application/" . APPLICATION_NAME . '/views/login/desktop/Login.class.php';
		}
		
		// BUILD THE VIEWs
		$login = new Login();
		$login->printTemplate();
		
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}
	
?>
