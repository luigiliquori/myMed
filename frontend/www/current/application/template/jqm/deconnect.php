<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	$request = new Request("SessionRequestHandler", DELETE);
	$request->addArgument("accessToken", $_SESSION['user']->session);
	$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);
	
	session_destroy();
	
	$responsejSon = $request->send();
	
	echo $responsejSon;
?>