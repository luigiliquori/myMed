<?php
	require_once('../../../lib/dasp/request/Request.class.php');
	require_once('../../../system/config.php');
	session_start();
	$responseObject = new stdClass(); $responseObject->success = false;

	//require_once('PhpConsole.php');
	//PhpConsole::start();

	
	$request = new Request("SubscribeRequestHandler", DELETE);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $_REQUEST['predicate']);
	$request->addArgument("userID", $_REQUEST['userID'] );
	if (isset($_REQUEST['accessToken']))
		$request->addArgument('accessToken', $_REQUEST['accessToken']);// to be able to unsubscribe from deconnected session (from email unsub link)
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if (isset($_REQUEST['accessToken'])){
		echo "Unsubscribed<br /><br />";
	}else if($responseObject->status == 200) {
		header("location: ./subscriptions.php?application=myTemplate");
	}else{
		//echo '<script type="text/javascript">$("[data-url=\"/application/jqm/publish.php\"]").html("Fail to publish: '.$responseObject->description.'");</script>';
		echo '<html><body>Failed to publish: '.$responseObject->description.'</html></body>';
		header("Refresh:1;url=http://mymed20.sophia.inria.fr/application/jqm/");
	}
	echo json_encode($responseObject);

?>