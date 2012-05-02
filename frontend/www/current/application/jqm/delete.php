<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	// DEBUG 
	require_once('PhpConsole.php');
	PhpConsole::start();
	
	$responseObject = new stdClass();
	
	$predicates = Array();
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i!='user' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
			$ontology = new stdClass();
			$ontology->key = $i;
			$ontology->value = $value;
			$ontology->ontologyID = $_REQUEST['_'.$i]; // '_'.$i form fields contain the ontologyID of the value
				
			array_push($predicates, $ontology);
		}
	}
	
	$request = new Request("PublishRequestHandler", DELETE);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", json_encode($predicates));
	if(isset($_REQUEST['user'])) {
		$request = new Request("ProfileRequestHandler", READ);
		$request->addArgument("id", $_REQUEST["user"]);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		$request->addArgument("user", json_encode($responseObject->dataObject->user));
	}else{
		$request->addArgument("user", json_encode($_SESSION['user']));
	}
	
	$responsejSon = $request->send();
	debug($responsejSon);
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>