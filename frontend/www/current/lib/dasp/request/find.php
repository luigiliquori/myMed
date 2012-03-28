<?php

	require_once('../../../system/config.php');
	session_start();
	
	$args = Array();
	$multipart = false;

	$args['application'] = $_REQUEST['application'];
	$args['code'] = '1';
	
	// Token for security - to access to the API
	if(isset($_SESSION['accessToken'])) {
		$args['accessToken'] = $_SESSION['accessToken'];
	}
	
	$predicate = "";
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $value && $value!='false' && $value!='' )
			$predicate .= $i . "(" . $value . ")";
	}
		
	$args['predicate'] = urlencode($predicate);
	
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

	// GET REQUEST
	curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
	curl_setopt($curl, CURLOPT_URL, BACKEND_URL."FindRequestHandler?".http_build_query($args));
	
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