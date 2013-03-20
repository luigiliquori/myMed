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