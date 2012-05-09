<?php
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');
	session_start();
	
	$predicate = "";
	foreach( $_REQUEST as $i => $value ){
		if ( $i!='application' && $i[0]!='_' && $value && $value!='false' && $value!='' ){
			$predicate .= $i . $value;
		}		
	}
	
	$request = new Request("SubscribeRequestHandler", CREATE);
	$request->addArgument("application", $_REQUEST['application']);
	$request->addArgument("predicate", $predicate);
	if(isset($_SESSION['user'])) {
		$request->addArgument("user", json_encode($_SESSION['user']));
	}
	
	$responsejSon = $request->send();
	$responseObject = json_decode($responsejSon);
	if($responseObject->status == 200) {
		header("location:.");
	}else{
		echo '<html><body>Failed to subscribe: '.$responseObject->description.'</html></body>';
		header("Refresh:1;url=http://mymed20.sophia.inria.fr/application/jqm/");
	}
	

?>