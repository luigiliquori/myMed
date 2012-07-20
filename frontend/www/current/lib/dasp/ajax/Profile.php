<?php
require_once('../request/Request.v2.php');
require_once('../../../system/config.php');

session_start();

/**
 * update profile with the just the first (K, V) found in GET
 */
if (count($_GET) > 0){

	$firstKey= key($_GET);
	$request = new Request("v2/ProfileRequestHandler", UPDATE);
	$request->addArgument("user", $_SESSION['user']->id);
	$request->addArgument("key", $firstKey);
	$request->addArgument("value", $_GET[$firstKey]);
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	
	/* update it in frontend session also */
	if ($responseObject->status==200){
		$_SESSION['user']->$firstKey = $_GET[$firstKey];
	}
	echo $responsejSon;
}

	
?>