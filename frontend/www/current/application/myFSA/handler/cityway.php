<?php 

// Cityway proxy handler

include dirname(__FILE__).'/../../../system/config.php';

// CALL TO CITYWAY API

if($_POST)
{
	
	$args = "&mode=transit" .
							"&depLon=" . $_POST['startlng'] .
							"&depLat=" . $_POST['startlat'] .
							"&depType=7" . 
							"&arrLon=" . $_POST['endlng'] .
							"&arrLat=" . $_POST['endlat'] .
							"&arrType=7" . 
							(isset($_POST['optimize'])?"&optimize=". $_POST['optimize']:"") .
							(isset($_POST['transitModes'])?"&transitModes=". $_POST['transitModes']:"") .
							"&departureTime=" . $_POST['date'];
	
	echo file_get_contents(Cityway_URL . "/tripplanner/v1/detailedtrip/json?key=" . Cityway_APP_ID . $args);
}else { }


?>