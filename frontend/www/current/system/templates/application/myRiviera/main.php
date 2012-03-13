<?php 

	// NAME OF THE APPLICATION
	define('APPLICATION_NAME', "myRiviera");
	
	// LOAD DASP JAVASCRIPT LIBRARY
	echo "<script src='lib/dasp/javascript/dasp.js'></script>";
	
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
