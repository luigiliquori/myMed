<?php 

	require_once 'system/templates/AbstractTemplate.class.php';
	AbstractTemplate::initializeTemplate("myRiviera");

	// LOAD THE VIEWs
	if(USER_CONNECTED) { // HOME PAGE OF THE APPLICATION ---------------------------
	
		// IMPORT THE MAIN VIEW
		require_once dirname(__FILE__).'/views/home/FindView.class.php';
		require_once dirname(__FILE__).'/views/home/OptionView.class.php';
		require_once dirname(__FILE__).'/views/dialog/EditDialog.class.php';

		// BUILD THE VIEWs
		$find = new FindView();
		$find->printTemplate();
		$option = new OptionView();
		$option->printTemplate();
		
		// DIALOG
		$edit = new EditDialog();
		$edit->getContent();
		
		// PROFILE UPDATE
		include('views/dialog/updateProfile.php');
	
	} else { // LOGIN PAGE OF THE APPLICATION ---------------------------
	
		// BUILD THE VIEWs
		$login = new Login();
		$login->printTemplate();
		
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}
	
?>
