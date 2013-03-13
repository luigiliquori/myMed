<?php 

// Cityway proxy handler
include dirname(__FILE__).'/../../../system/config.php';

// CALL TO CITYWAY API


	$args = "&mode=transit" .
		"&depLon=" . $_GET['depLon'] .
		"&depLat=" . $_GET['depLat'] .
		"&depType=7" . 
		"&arrLon=" . $_GET['arrLon'] .
		"&arrLat=" . $_GET['arrLat'] .
		"&arrType=7" . 
		(isset($_GET['optimize'])?"&optimize=". $_GET['optimize']:"") .
		(isset($_GET['transitModes'])?"&transitModes=". $_GET['transitModes']:"") .
		"&departureTime=" . $_GET['departureTime'];
	
	echo file_get_contents(Cityway_URL . "/tripplanner/v1/detailedtrip/json?key=" . Cityway_APP_ID . $args);



?>