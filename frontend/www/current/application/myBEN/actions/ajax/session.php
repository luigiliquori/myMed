<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success=false;
	
	$request = new Request("SessionRequestHandler", READ);
	if(isset($_REQUEST['accessToken'])){
		$request->addArgument("socialNetwork", $_REQUEST['accessToken']);
	} else {
		$request->addArgument("socialNetwork", "myMed");
	}
	if(isset($_SESSION['accessToken'])) {

		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$responseObject->success=true;
			$_SESSION['user'] = $responseObject->dataObject->user;
			if(!isset($_SESSION['friends'])){
				$_SESSION['friends'] = array();
			}
		}
	}
	
	echo json_encode($responseObject);

?>