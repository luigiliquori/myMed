<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	$request = new Request("SessionRequestHandler", DELETE);
	$request->addArgument("accessToken", $_SESSION['user']->session);
	$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}

	session_destroy();
	
	echo json_encode($responseObject);
	

?>