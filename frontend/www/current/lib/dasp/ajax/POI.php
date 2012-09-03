<?php

require_once('../request/Requestv2.php');
require_once('../../../system/config.php');

// READ
if(!isset($_REQUEST["locationId"])) {
	$request = new Requestv2("POIRequestHandler", READ);
	$request->addArgument("application", $_REQUEST["application"]);
	$request->addArgument("type", $_REQUEST["type"]);
	$request->addArgument("latitude", $_REQUEST["latitude"]);
	$request->addArgument("longitude", $_REQUEST["longitude"]);
	$request->addArgument("radius", $_REQUEST["radius"]);
	session_start();
	$responsejSon = $request->send();
	session_write_close();

	// DELETE
} else {
	$request = new Requestv2("POIRequestHandler", DELETE);
	$request->addArgument("application", $_REQUEST["application"]);
	$request->addArgument("type", $_REQUEST["type"]);
	$request->addArgument("locationId", $_REQUEST["locationId"]);
	$request->addArgument("itemId", $_REQUEST["itemId"]);
	session_start();
	$responsejSon = $request->send();
	session_write_close();
}

echo $responsejSon;

?>