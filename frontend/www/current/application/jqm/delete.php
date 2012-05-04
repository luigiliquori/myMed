<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	$user = "";
	if(isset($_REQUEST['user'])) {
		$request = new Request("ProfileRequestHandler", READ);
		$request->addArgument("id", $_REQUEST["user"]);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$user = json_encode($responseObject->dataObject->user);
	}else{
		$user = json_encode($_SESSION['user']);
	}
	
	$request = new Request("PublishRequestHandler", DELETE);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", json_encode($_REQUEST['predicates']));
	$request->addArgument("user", $user );
	
	$responsejSon = $request->send();
	debug($responsejSon);
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>