<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once '../../lib/dasp/beans/MPositionBean.class.php';
	require_once('../../system/config.php');
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success = false;
	
	if (isset($_SESSION['user'])){
		$request = new Request("PositionRequestHandler", isset($_REQUEST['latitude'])?UPDATE:READ);
	
		if (isset($_REQUEST['latitude'])){
			$position = new MPositionBean();
			$position->userID = $_SESSION['user']->id;
			$position->latitude = $_REQUEST["latitude"];
			$position->longitude = $_REQUEST["longitude"];
			$position->formattedAddress = $_REQUEST["formatted_address"];
		
			$request->addArgument("position", json_encode($position));
		}else{
			$request->addArgument("userID", $_SESSION['user']->id);
		}
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$responseObject->success = true;
		}
	}
	
	echo json_encode($responseObject);

?>