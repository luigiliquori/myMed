<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	unset($_SESSION['user']);
	unset($_SESSION['accessToken']);
	$responseObject->success = true;
	echo json_encode($responseObject);
	

?>