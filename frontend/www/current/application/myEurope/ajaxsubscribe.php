<?php
	session_start();
	require_once '../../lib/dasp/request/Request.class.php';
	require_once '../../system/config.php';
	
	if ($_GET['code'] == 0){
		$request = new Request("SubscribeRequestHandler", CREATE);
		$request->addArgument("application", $_GET['application']);
		$request->addArgument("predicate", urldecode($_GET['predicate']));
		$request->addArgument("user", json_encode($_SESSION['user']));
		$responsejSon = $request->send();
		echo $responsejSon;
		
	} else if ($_GET['code'] == 3 ){
		
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", $_GET['application']);
		$request->addArgument("predicate", urldecode($_GET['predicate']));
		$request->addArgument("userID", $_SESSION['user']->id );
		$responsejSon = $request->send();
		echo $responsejSon;
		
	} else {
		
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", $_GET['application']);
		$request->addArgument("userID", $_SESSION['user']->id);
		
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