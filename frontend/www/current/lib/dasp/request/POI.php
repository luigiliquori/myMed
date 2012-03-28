<?php

	require_once('../../../system/config.php');
	//require_once('PhpConsole.php');
	//PhpConsole::start();
	session_start();
	
	$args = Array();
	$multipart = false;
	
	//echo '<script type="text/javascript">alert(\' res ' . $_REQUEST['keyword'] . ' '. $_REQUEST['application'].  '\');</script>';
	
	$curl	= curl_init();
	if($curl === false) {
		trigger_error('Unable to init CURL : ', E_USER_ERROR);
	}
	
	//debug($_REQUEST['keyword'].' - '.$_REQUEST['gps'].' - '.$_REQUEST['date'].' - '.$_REQUEST['application']);

	
	$args['type'] = $_REQUEST['type'];
	$args['latitude'] = $_REQUEST['latitude'];
	$args['longitude'] = $_REQUEST['longitude'];
	$args['radius'] = $_REQUEST['radius'];
	
	$args['application'] = $_REQUEST['application'];
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if($multipart){
		$httpHeader[] = 'Content-Type:multipart/form-data';
	} else {
		$httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
	}
	$args['code'] = '1';
	
	// Token for security - to access to the API
	if(isset($_SESSION['accessToken'])) {
		$args['accessToken'] = $_SESSION['accessToken'];
	}
	
	//debug(BACKEND_URL);

	// GET REQUEST
	curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
	curl_setopt($curl, CURLOPT_URL, BACKEND_URL."POIRequestHandler?".http_build_query($args));
	
	// SSL CONNECTION
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
	curl_setopt($curl, CURLOPT_CAINFO, "/etc/ssl/certs/mymed.crt"); // TO EXPORT FROM GLASSFISH!
	
	$responsejSon = curl_exec($curl);
	
	echo $responsejSon;

?>