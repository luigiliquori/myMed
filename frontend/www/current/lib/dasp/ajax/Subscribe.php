<?php
require_once '../request/Requestv2.php';
session_start();


$request = new Requestv2("v2/SubscribeRequestHandler", $_GET['code'], $_GET);
$request->addArgument("user", $_SESSION['user']->id);
$responsejSon = $request->send();

$subscriptions = json_decode($responsejSon);
$res = array("sub"=>false);
if($subscriptions->status == 200) {
	if (isset($subscriptions->dataObject->subscriptions)){
		$subscriptions = (array) $subscriptions->dataObject->subscriptions;
		$sub = urldecode($_GET['predicate']);
		foreach( $subscriptions as $k => $value ){
			if ($k == $sub ){
				$res["sub"] = true;
				break;
			}
		}
		echo json_encode($res);
	} else {
		echo json_encode(array("success"=>true));
	}
	
}


?>
