<?php
	
	require_once '../request/Request.class.php';
	require_once '../../../system/config.php';
	
	if ($_GET['code'] == 2){
		$request = new Request("PositionRequestHandler", UPDATE);
		session_start();
		$request->addArgument("userID", $_SESSION['user']->id);
		$request->addArgument("position", $_POST['position']);
		$responsejSon = $request->send();
		session_write_close();
		echo $responsejSon;
		
	} else {
		$request = new Request("PositionRequestHandler", READ);
		session_start();
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();
		session_write_close();
		echo $responsejSon;

		
	}
	
?>