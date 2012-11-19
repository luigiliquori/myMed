<?php
require_once('../request/RequestJson.php');
require_once('../../../system/config.php');

if (!isset($_POST["predicates"]))
	$_POST["predicates"] = array();
else
	$_POST["predicates"] = json_decode($_POST["predicates"]);

$request = new RequestJson(null, $_POST, CREATE, "v2/SubscribeRequestHandler"  );

session_start();

$request->addArgument("user", $_SESSION['user']->id);
	
$responsejSon = $request->send();

$pStr = "";
foreach ($_POST["predicates"] as $p){
	$pStr .= str_replace('|', '+', $p->value).' ';
}

if (empty($pStr)){
	$pStr = "all";
}

$responsejSon->description = _("Subscribed to: ").(count($_POST["predicates"])?$pStr:$_POST['id']);

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
