<?php
	
	//ob_start("ob_gzhandler");
	if(isset($_GET['registration']) || isset($_GET['userID'])) { //registration confirmation or unsub, (from emails)
		// registrattion validation
		header("Location: ./controller?". $_SERVER['QUERY_STRING']);
	} else {
		header("Location: ./search". (strlen($_SERVER['QUERY_STRING'])?"?".$_SERVER['QUERY_STRING']:""));
	}
	
?>
