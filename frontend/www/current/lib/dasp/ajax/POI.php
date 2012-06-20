<?php

	require_once('../request/Request.class.php');
	require_once('../../../system/config.php');
	
	$request = new Request("POIRequestHandler", READ);
	$request->addArgument("application", $_REQUEST["application"]);
	$request->addArgument("type", $_REQUEST["type"]);
	$request->addArgument("latitude", $_REQUEST["latitude"]);
	$request->addArgument("longitude", $_REQUEST["longitude"]);
	$request->addArgument("radius", $_REQUEST["radius"]);
	
	session_start();
	$responsejSon = $request->send();
	session_write_close();
	
	echo $responsejSon;

?>