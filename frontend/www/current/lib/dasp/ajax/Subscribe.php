<?php

require_once '../request/Request.v2.php';
require_once '../../../system/config.php';
session_start();


$data=array();


if ($_GET['code'] == CREATE){ 
	/*
	 * create subscription for user at this predicate
	 */
	
	
	
	$request = new Request("v2/SubscribeRequestHandler", CREATE);
	$request->addArgument("application", $_GET['application']);
	$request->addArgument("namespace", $_GET['namespace']);
	$request->addArgument("user", $_SESSION['user']->id);
	$request->addArgument("data", urldecode($_GET['data']));
	$responsejSon = $request->send();
	echo $responsejSon;

} else if ($_GET['code'] == DELETE ){
	/*
	 * remove it's subscription for that predicate
	 */
	$request = new Request("v2/SubscribeRequestHandler", DELETE);
	$request->addArgument("application", $_GET['application']);
	$request->addArgument("predicate", urldecode($_GET['predicate']));
	$request->addArgument("user", $_SESSION['user']->id );
	$responsejSon = $request->send();
	echo $responsejSon;

} else {
	/*
	 * answer {"sub": true} if user is subscribed for this predicate
	 */
	$request = new Request("v2/SubscribeRequestHandler", READ);
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