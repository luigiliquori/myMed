<?php 

	/*
	 * 
	 * put all handlers in search, detail, option ... 
	 * here
	 * 
	 * -> MVC pattern
	 * 
	 */
	require_once('../../lib/dasp/request/Request.class.php');
	require_once('../../system/config.php');

	
	if($_POST['method'] == "publish") {
		//
		//header("Location: ./search");
	} else if($_POST['method'] == "subscribe") {
		//
		//echo $res;
	} else if($_POST['method'] == "delete") {
		//
		//echo $res;
	} else if($_POST['method'] == "authenticate") {
		
	} else if($_POST['method'] == "register") {
		
	} else if($_POST['method'] == "startsession") {
		
	} else if($_POST['method'] == "updateprofile") {
		
	} else if($_POST['method'] == "startInteraction") {

	}

?>