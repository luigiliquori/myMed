<?php
require_once '../request/Requestv2.php';
require_once '../../../system/config.php';
session_start();

if ($_GET['code'] == CREATE){
	/*
	 * create subscription for user at this predicate
	*/

	$request = new Requestv2("v2/SubscribeRequestHandler", CREATE);
	$request->addArgument("application", $_GET['application']);
	$request->addArgument("namespace", $_GET['namespace']);
	$request->addArgument("user", $_SESSION['user']->id);
	if (isset( $_GET['id']))
		$request->addArgument("id", $_GET['id']);
	else
		$request->addArgument("index", $_GET['index']);
	$responsejSon = $request->send();
	echo $responsejSon;

} else if ($_GET['code'] == DELETE ){
	/*
	 * remove it's subscription for that predicate
	*/
	$request = new Requestv2("v2/SubscribeRequestHandler", DELETE);
	$request->addArgument("application", $_GET['application']);
	$request->addArgument("namespace", $_GET['namespace']);
	$request->addArgument("user", $_SESSION['user']->id );
	$request->addArgument("index", $_GET['index']);
	$responsejSon = $request->send();
	echo $responsejSon;

} else {
	/*
	 * answer {"sub": true} if user is subscribed for this predicate
	*/
	$request = new Requestv2("v2/SubscribeRequestHandler", READ);
	$request->addArgument("application", $_GET['application']);
	$request->addArgument("namespace", $_GET['namespace']);
	$request->addArgument("user", $_SESSION['user']->id);

	$responsejSon = $request->send();
	$subscriptions = json_decode($responsejSon);
	$res = array("sub"=>false);
	if($subscriptions->status == 200) {
		$subscriptions = (array) $subscriptions->dataObject->subscriptions;
		$sub = urldecode($_GET['predicate']);
		foreach( $subscriptions as $k => $value ){
			if ($k == $sub ){
				$res["sub"] = true;
				break;
			}
		}
		echo json_encode($res);
	}
}

?>
