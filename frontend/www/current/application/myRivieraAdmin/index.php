<?php
	// page compression
	ob_start("ob_gzhandler");	
	// for the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// DEBUG
	ini_set('display_errors', 1);
	
	session_start();
	
	// GET ALL THE API KEYs AND ADDRESS
	require_once dirname(__FILE__).'/../../system/config.php';
	
	define('APPLICATION_NAME', "myRivieraAdmin");
	define('USER_CONNECTED', isset($_SESSION['user']));
	

	include ('header.php');
	
	// IMPORTS ALL THE HANDLER
	require_once dirname(__FILE__).'/handler/MyApplicationHandler.class.php';	new MyApplicationHandler();
	require_once dirname(__FILE__).'/handler/InscriptionHandler.class.php';		$inscription = new InscriptionHandler();
	require_once dirname(__FILE__).'/handler/LoginHandler.class.php';			new LoginHandler();
	require_once dirname(__FILE__).'/handler/MenuHandler.class.php';			new MenuHandler();
	require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';	new UpdateProfileHandler();
	
	// IMPORTS ALL THE VIEWS
	require_once dirname(__FILE__).'/views/AbstractView.class.php';	
	if(USER_CONNECTED) {
		// DEFINE ATTRIBUTES FOR THE JAVASCRIPT PART (AJAX CALL)
		// TODO REMOVE THIS
		echo "<input type='hidden' id='userID' value='" . $_SESSION['user']->id . "' />";
		echo "<input type='hidden' id='applicationName' value='" . APPLICATION_NAME . "' />";
		echo "<input type='hidden' id='accessToken' value='" . $_SESSION['accessToken'] . "' />";
		require_once dirname(__FILE__).'/views/home/PublishView.class.php';		new PublishView();
	} else {
		require_once dirname(__FILE__).'/views/login/mobile/Login.class.php';	new Login($inscription);
	}
	
	include ('footer.php');
	
?>
