<?php

define('APP_ROOT', '.');
define('MYMED_ROOT', '../../');
define('APPLICATION_NAME', 'stressTest');
define('USER_ID', 'myStressTest@mymed.com');
define('ACCESS_TOKEN', hash('sha512', 'myStressTest@mymed.com'));

require(MYMED_ROOT 		. 'system/common/init.php');
require(MYMED_ROOT 		. 'tests/stress/TestObject.class.php');
require_once MYMED_ROOT . '/system/config.php';
require_once MYMED_ROOT . '/system/common/common-utils.php';

// Fill object with POST values
function fillObj($obj) {
	$obj->pred1 = $_REQUEST['pred1'];
	$obj->pred2 = $_REQUEST['pred2'];
	$obj->pred3 = $_REQUEST['pred3'];

	$obj->data = $_REQUEST['data'];
}

// ---------------------------------------------------------------------
// Main process
// ---------------------------------------------------------------------
// Start session
session_start();

// Setup the credential
if(isset($_REQUEST['accessToken']) && isset($_REQUEST['userID'])) {
	$user_mymedWrapper = new MUserBean();
	$user_mymedWrapper->id = $_REQUEST['userID'];
	$_SESSION['accessToken'] = $_REQUEST['accessToken'];
	$_SESSION['user'] = $user_mymedWrapper;
}

if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Initialize") {
	// Create a fake myMed session for the tests
	$request = new Request("SessionRequestHandler", CREATE);
	$request->addArgument("userID", USER_ID);
	$request->addArgument("accessToken", ACCESS_TOKEN);
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);

	if($responseObject->status == 200) {
		// create a fake user for the tests
		$user_mymedWrapper = new MUserBean();
		$user_mymedWrapper->id = USER_ID;
		$request = new Request("ProfileRequestHandler", CREATE);
		$request->addArgument("user", json_encode($user_mymedWrapper));
		$request->addArgument("accessToken", ACCESS_TOKEN);
		
		$_SESSION['accessToken'] = ACCESS_TOKEN;
		$_SESSION['user'] = $user_mymedWrapper;
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status == 200) {
			echo '{"userID" : "' . USER_ID .'", "accessToken" : "' . ACCESS_TOKEN .'"}';
		} else {
			echo $responsejSon;
		}
	} else {
		echo $responsejSon;
	}
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {

	// -- Publish
	$obj = new TestObject();
	fillObj($obj);
	try {
		$startTime = microtime(true);
		$obj->publish();
		$endTime = microtime(true);
		if($endTime >= $startTime) {
			echo $endTime - $startTime;
		} else {
			throw new Exception("Time measurement not valid!");
		}
	} catch (Exception $e) {
		echo -1;
	}

} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Find") {

	// -- Search
	$search = new TestObject();
	fillObj($search);
	try {
		$startTime = microtime(true);
		$resArray = $search->find();
		$endTime = microtime(true);
		if($endTime >= $startTime) {
			echo $endTime - $startTime;
		} else {
			throw new Exception("Time measurement not valid!");
		}
	} catch (Exception $e) {
		echo -1;
	}
}

?>

