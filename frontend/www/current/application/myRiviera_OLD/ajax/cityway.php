<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
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