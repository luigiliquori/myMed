<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	$responseObject = new stdClass(); $responseObject->success = false;
	
	if(!isset($_SESSION['user'])) {
		$request = new Request("AuthenticationRequestHandler", READ);
		$request->addArgument("login", $_REQUEST["login"]);
		$request->addArgument("password", hash('sha512', $_REQUEST["password"]));
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
			//$_SESSION['user'] = $responseObject->dataObject->user;
			$request = new Request("SessionRequestHandler", READ);
			$request->addArgument("socialNetwork", "myMed");
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			if($responseObject->status == 200) {
				$responseObject->success = true;
				$_SESSION['user'] = $responseObject->dataObject->user;
				if(!isset($_SESSION['friends'])){
					$_SESSION['friends'] = array();
				}
			}
			
		}else{
			//header("Refresh:1;url=/application/jqm?hello");
		}
		
	}

	echo json_encode($responseObject);

?>