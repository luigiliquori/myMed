<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	function cmp($a, $b)
	{
		return strcmp($a->key, $b->key);
	}
	
	$responseObject = new stdClass();$responseObject->success = false;
	
	$predicates = Array();
	$data = Array();
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
			$ontology = new stdClass();
			$ontology->key = $i;
			$ontology->value = $value;
			$ontology->ontologyID = $_REQUEST['_'.$i]; // '_'.$i form fields contain the ontologyID of the value
				
			if(($ontology->ontologyID < 4)){
				array_push($predicates, $ontology);
			}else{
				array_push($data, $ontology);
			}
			
		}
	}
	usort($predicates, "cmp"); // VERY important, to be able to delete the exact same predicates later
	$data = array_merge($predicates, $data);
	
	$request = new Request("PublishRequestHandler", CREATE);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", json_encode($predicates));
	$request->addArgument("data", json_encode($data));
	if(isset($_SESSION['user'])) {
		$request->addArgument("user", json_encode($_SESSION['user']));
	}
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		$responseObject->success = true;
	}

	header("location:./#Find");
	//echo json_encode($responseObject);

?>