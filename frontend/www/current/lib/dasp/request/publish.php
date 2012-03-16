<?php

	require_once('../../../system/config.php');;
	session_start();
	
	$args = Array();
	$multipart = false;
	
	$predicates = Array();
	$data = Array();

	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && $value!='false' && $value!='' ){
			$ontology = new stdClass();
			$ontology->key = $i;
			$ontology->value = $value;
			$ontology->ontologyID = $_REQUEST['_'.$i]; // '_'.$i form fields contain the ontologyID of the value
			
			array_push($data, $ontology);
			if ($ontology->ontologyID < 4)
				array_push($predicates, $ontology);
		}
	}
	
	$args['application'] = $_REQUEST['application'];
	$args['code'] = '0';
	$args['user'] = urlencode(json_encode($_SESSION['user']));
	$args['predicate'] = urlencode(json_encode($predicates));
	$args['data'] = urlencode(json_encode($data));
	
	// Token for security - to access to the API
	if(isset($_SESSION['accessToken'])) {
		$args['accessToken'] = $_SESSION['accessToken'];
	}

	$curl	= curl_init();
	if($curl === false) {
		trigger_error('Unable to init CURL : ', E_USER_ERROR);
	}

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if($multipart){
		$httpHeader[] = 'Content-Type:multipart/form-data';
	} else {
		$httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
	}
	
	// POST REQUEST
	curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
	curl_setopt($curl, CURLOPT_URL, BACKEND_URL."PublishRequestHandler");
	curl_setopt($curl, CURLOPT_POST, true);
	if($multipart){
		curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
	} else {
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($args));
	}

	// SSL CONNECTION
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
	curl_setopt($curl, CURLOPT_CAINFO, "/etc/ssl/certs/mymed.crt"); // TO EXPORT FROM GLASSFISH!
	
	$responsejSon = curl_exec($curl);
	$responseObject = json_decode($responsejSon);
	
	if($responseObject->status != 200) {
		$responseObject->success = false;
	} else {
		$responseObject->success = true;
	}
	
	echo json_encode($responseObject);

?>