<?php

	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	session_start();
	
	$responseObject = new stdClass();
	
	$request = new Request("POIRequestHandler", READ);
	
	$request->addArgument("application", $_REQUEST["application"]);
	$request->addArgument("type", $_REQUEST["type"]);
	$request->addArgument("latitude", $_REQUEST["latitude"]);
	$request->addArgument("longitude", $_REQUEST["longitude"]);
	$request->addArgument("radius", $_REQUEST["radius"]);
	
	$responsejSon = $request->send();
	//debug($responsejSon);
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>