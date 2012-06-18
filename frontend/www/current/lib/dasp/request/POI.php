<?php
	require_once('Request.class.php');
	require_once('../../../system/config.php');
	session_start();
	
	$responseObject = new stdClass();
	
	// READ
	if(!isset($_REQUEST["locationId"])) {
		$request = new Request("POIRequestHandler", READ);
		$request->addArgument("application", $_REQUEST["application"]);
		$request->addArgument("type", $_REQUEST["type"]);
		$request->addArgument("latitude", $_REQUEST["latitude"]);
		$request->addArgument("longitude", $_REQUEST["longitude"]);
		$request->addArgument("radius", $_REQUEST["radius"]);
		$responsejSon = $request->send();
	
	// DELETE
	} else {
		$request = new Request("POIRequestHandler", DELETE);
		$request->addArgument("application", $_REQUEST["application"]);
		$request->addArgument("type", $_REQUEST["type"]);
		$request->addArgument("locationId", $_REQUEST["locationId"]);
		$request->addArgument("itemId", $_REQUEST["itemId"]);
		$responsejSon = $request->send();
	}
	
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>