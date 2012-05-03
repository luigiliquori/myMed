<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	if(!isset($_SESSION['user'])) {
		$request = new Request("AuthenticationRequestHandler", READ);
		$request->addArgument("login", $_REQUEST["login"]);
		$request->addArgument("password", hash('sha512', $_REQUEST["password"]));
		
		$responsejSon = $request->send();
		debug($responsejSon);
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$responseObject->success = true;
			$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
			$_SESSION['user'] = $responseObject->dataObject->user;
			
			//header("Refresh:1;url=/application/jqm?hello");
			
			//$responseObject->data->accessToken = $responseObject->dataObject->accessToken = null;
		}else{
			//header("Refresh:1;url=/application/jqm?hello");
		}
		
		//header("location:./");
	}
	/*debug($_REQUEST["login"]);
	
	header("location:./?hello");
	header("Refresh:1;url=/application/jqm?hello");
	header("Refresh:0;url=".$_SERVER['SERVER_NAME']);*/
	echo json_encode($responseObject);

?>