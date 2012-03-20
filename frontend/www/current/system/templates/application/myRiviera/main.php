<?php 

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myRiviera");
	
	// LOAD THE UI FRAMEWORK
	echo '<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />';
	echo '<link rel="stylesheet" href="lib/jquery/jquery.mobile.actionsheet.css" />';
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>';
	echo '<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>';
	echo '<script type="text/javascript" src="lib/jquery/jquery.mobile.actionsheet.js"></script>';
	echo '<script src="lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>';
	echo '<link href="lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />';
	echo '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key='. Google_APP_SECRET .'&sensor=true&libraries=places"></script>';
	echo '<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>';
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
	// APPLICATION STUFF
	echo '<link rel="stylesheet" href="system/templates/application/' . APPLICATION_NAME . '/css/style.css" />';
	echo '<script src="system/templates/application/' . APPLICATION_NAME . '/javascript/myRiviera.js"></script>';
	
	// LOAD THE VIEWs
	if(USER_CONNECTED) { // HOME PAGE OF THE APPLICATION ---------------------------
	
		// IMPORT THE HANDLER
		require_once dirname(__FILE__).'/handler/MenuHandler.class.php';
		$menuHandler = new MenuHandler();
		$menuHandler->handleRequest();
		require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';
		$updateHandler = new UpdateProfileHandler();
		$updateHandler->handleRequest();
	
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/FindView.class.php';
		require_once dirname(__FILE__).'/views/home/OptionView.class.php';
		require_once dirname(__FILE__).'/views/dialog/EditDialog.class.php';
	
		// DISCONNECT FORM
		echo '<form action="?application=' . APPLICATION_NAME . '" method="post" name="disconnectForm" id="disconnectForm">';
		echo '<input type="hidden" name="disconnect" value="1" /></form>';
	
		// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
		echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
		echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
		echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
	
		// BUILD THE VIEWs
		$find = new FindView();
		$find->printTemplate();
		$option = new OptionView();
		$option->printTemplate();
		
		// DIALOG
		$edit = new EditDialog();
		$edit->getContent();
		
		// PROFILE UPDATE
		include('system/templates/container/mobile/home/views/updateProfile.php');
	
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
