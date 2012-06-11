<?php

	// DEBUG
	ini_set('display_errors', 1);

	// page compression
	ob_start("ob_gzhandler");			
	
	// for the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// start session
	session_start();
	
	// Constantes
	if(!defined('MYMED_ROOT')){
		define('MYMED_ROOT', __DIR__ . '/../../');
	}

	set_include_path(get_include_path().PATH_SEPARATOR.MYMED_ROOT);
	set_include_path(get_include_path().PATH_SEPARATOR.MYMED_ROOT . '/application/myMed/');
	
	define('USER_CONNECTED', isset($_SESSION['user']));
	define('APPLICATION_NAME', "myMed");
	
	require_once MYMED_ROOT . 'system/config.php';
	require_once 'views/AbstractView.class.php';
	
	include('header.php');
	
	if(USER_CONNECTED) { // HOME PAGE ---------------------------
		
		// IMPORT THE MAIN VIEW
		require_once 'views/home/Home.class.php';
		require_once 'views/home/Profile.class.php';
		
		// BUILD THE VIEWs
		$home = new Home();
		$home->printTemplate();
		$profile = new Profile();
		$profile->printTemplate();
		include('views/dialog/updateProfile.php');
		
	} else { // LOGIN PAGE ---------------------------
		
		// IMPORT THE LOGIN VIEW
		require_once 'views/login/Login.class.php';
		
		// BUILD THE VIEWs
		$login = new Login();
		$login->printTemplate();
		
		include('views/dialog/socialNetwork.php');
		include('views/dialog/condition.php');
	}
	
	include('footer.php');
	
?>

