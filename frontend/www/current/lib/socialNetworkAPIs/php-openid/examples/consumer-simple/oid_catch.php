<?php

require_once "oid-common.php";

// Starts session (needed for YADIS)
session_start();

// Create file storage area for OpenID data
$store = new Auth_OpenID_FileStore(getStorePath());

// Create OpenID consumer
$consumer = new Auth_OpenID_Consumer($store);

// Create an authentication request to the OpenID provider
$response = $consumer->complete('http://mymed20.sophia.inria.fr/lib/socialNetworkAPIs/php-openid/examples/consumer-simple/oid_catch.php');

if ($response->status == Auth_OpenID_SUCCESS) {
	// Get registration informations
	$ax = new Auth_OpenID_AX_FetchResponse();
	$obj = $ax->fromSuccessResponse($response);
	
	//should fetch Sreg also here
	
	require_once ROOT.'lib/dasp/beans/MUserBean.class.php';
	
	$_SESSION['user'] = MUserBean::constructFromOpenId($obj->data);
	$_SESSION['accessToken'] = sha1($_SESSION['user']->email . time());
	header('Location: ./../../../../../?action=login');


} else {
	print('failed ;(');
}


?>