<?php
require_once '../request/Requestv2.php';
require_once '../../../system/config.php';
session_start();

if ($_GET['code'] == CREATE){
	/*
	 * create subscription for user at this predicate
	*/

	$request = new Requestv2("v2/SubscribeRequestHandler", CREATE, $_GET);
	$request->addArgument("user", $_SESSION['user']->id);
	$responsejSon = $request->send();
	echo $responsejSon;

} else if ($_GET['code'] == DELETE ){
	/*
	 * remove it's subscription for that predicate
	*/
	$request = new Requestv2("v2/SubscribeRequestHandler", DELETE, $_GET);

	$request->addArgument("user", $_SESSION['user']->id );

	$responsejSon = $request->send();
	echo $responsejSon;

} else {
	/*
	 * answer {"sub": true} if user is subscribed for this predicate
	*/
	$request = new Requestv2("v2/SubscribeRequestHandler", READ, $_GET);
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
