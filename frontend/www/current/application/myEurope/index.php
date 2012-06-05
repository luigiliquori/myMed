<?php
	
	//ob_start("ob_gzhandler");
	if(isset($_GET['registration'])) { //or unsub?
		// registrattion validation
		header("Location: ./controller?". $_SERVER['QUERY_STRING']);
	} else {
		header("Location: ./search?". $_SERVER['QUERY_STRING']);
	}
	
?>
