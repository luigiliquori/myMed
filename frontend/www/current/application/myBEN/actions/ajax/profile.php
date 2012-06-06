<?php

	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success = false;
	
	$request = new Request("ProfileRequestHandler", READ);
	$request->addArgument("id", $_REQUEST["id"]);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>