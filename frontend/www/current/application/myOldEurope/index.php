<?php
	
	//ob_start("ob_gzhandler");
	session_start();

	if (isset($_SESSION['user'])) {
		header("Location: ./search". (strlen($_SERVER['QUERY_STRING'])?"?".$_SERVER['QUERY_STRING']:""));
	} else if (isset($_GET['registration']) || (isset($_GET['userID']))) {
		header("Location: ./option?".$_SERVER['QUERY_STRING']);
	} else {
		header("Location: ./authenticate");
	}
	
	
	
?>
