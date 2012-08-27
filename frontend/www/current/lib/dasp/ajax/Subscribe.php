<?php
require_once('../request/RequestJson.php');
require_once('../../../system/config.php');

$request = new RequestJson(null, $_POST, CREATE, "v2/SubscribeRequestHandler");

session_start();

$request->addArgument("user", $_SESSION['user']->id);
if (!$request->hasArgument("predicates"))
	$request->addArgument("predicates", array());
	
$responsejSon = $request->send();

echo json_encode($responsejSon);


/*$res = array("sub"=>false);
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
	
}*/


?>
