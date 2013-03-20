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
<?

define('EXTENDED_PROFILE_PREFIX' , '_extended');
define('STORE_PREFIX' , '_store');

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {

	public static $hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp", "myMed_old", "myOldEurope", "myTemplate");
	public static $bootstrapApplication = array("myRiviera", "myFSA", "myEurope", "myMemory", "myBen", "myEuroCIN");

	public $applicationList = array();
	public $applicationStatus = array();

	public $reputation = array();

	protected $currentSuccessMess = null;
	protected $currentErrorMess = null;

	public function __construct() {

	}

	function resetApplicationStatus(){
		$this->applicationStatus = array();
		foreach($this->applicationList as $app) {
			$this->applicationStatus[$app] = "off";
		}
	}

	public function handleRequest() {

		parent::handleRequest();

		// If the user is a guest, forward to login
		if (isset($_SESSION['user']) && $_SESSION['user']->is_guest) {
			$this->forwardTo('login');
		}
		
		// Set the flag
		$_SESSION["launchpad"] = true;
		
		// SUBSCRIBE
		if(isset($_POST['method']) && $_POST['method'] == "subscribe") {
			$subscribe = new Subscribe($this);
			$subscribe->send();

			// ADD GROUP OF POIs
		} else if(isset($_POST['addPOI'])){

			if(isset($_POST['data'])) {

				$pois = json_decode($_POST['data']);

				foreach($pois as $poi){
						
					$request = new Request("POIRequestHandler", CREATE);
					$request->addArgument("application", APPLICATION_NAME);
					$request->addArgument("user", $_SESSION["user"]->id);
					$request->addArgument("accessToken", $_SESSION["accessToken"]);

					if(isset($poi->features)) {
						foreach ($poi->features as $feature) {
								
							// needed parameters
							if(isset($feature->geometry)) {							// CARF POIs
								$longitude = $feature->geometry->coordinates[0];
								$latitude  = $feature->geometry->coordinates[1];
							} else if (isset($feature->properties->Longitude)) { 	// Students POIs
								$longitude = $feature->properties->Longitude;
								$latitude  = $feature->properties->Latitude;
							} else if (isset($feature->properties->Adresse)) {   	// FSA	POIs
								$address = $feature->properties->Adresse;
								$address = str_replace(" ", "+", $address);
								$address = preg_replace('/\s*\([^)]*\)/', '', $address);
								$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
								$json = json_decode($json);
								$latitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
								$longitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
							} else {
								continue;
							}
							
							$latitude = str_replace(',', '.', $latitude);
							$longitude = str_replace(',', '.', $longitude);
							$type = 		isset($feature->properties->Type) ? $feature->properties->Type : "";

							// other parameters
							$title = 		isset($feature->properties->Nom) ? 			str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Nom) : "";
							$sousType = 	isset($feature->properties->SousType) ? 	str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->SousType) : "";
							$description =  isset($feature->properties->Description) ? 	str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Description) : "";
							$icon =  		isset($feature->properties->Icon) ? 		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Icon) : "";
							$address = 		isset($feature->properties->Adresse) ? 		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Adresse) : "";
							$email = 		isset($feature->properties->Email) ? 		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Email) : "";
							$link = 		isset($feature->properties->Link) ? 		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Link) : "";
							$idMedia = 		isset($feature->properties->IdMedia) ? 		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->IdMedia) : "";
							$comUrbaine = 	isset($feature->properties->ComUrbaine) ? 	str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->ComUrbaine) : "";
							$altitude = 	isset($feature->properties->Altitude) ?		str_replace(array("\r", "\r\n", "\n", "\""), '',$feature->properties->Altitude) : "";
							
							$request->addArgument("longitude", $longitude);
							$request->addArgument("latitude", $latitude);
							$request->addArgument("type", $type);
								
							$value = '{' .
								'"longitude" : "'. 		$longitude 	 	.'",' .
								'"latitude" : "'. 		$latitude 		.'",' .
								'"type" : "'.			$type 			.'",' .
								'"sousType" : "'.		$sousType 		.'",' .
								'"title" : "'. 			$title 		  	.'",' .
								'"description" : "'. 	$description	.'",' .
								'"icon" : "' . 			$icon 			.'",' .
								'"adresse" : "'. 		$address 		.'",' .
								'"email" : "'. 			$email 			.'",' .
								'"link" : "'. 			$link 			.'",' .
								'"idMedia" : "'. 		$idMedia 		.'",' . 
								'"comUrbaine" : "'.		$comUrbaine 	.'",' .  
								'"altitude" : "'.		$altitude		.'"' .
								'}';
							
							$request->addArgument("value", $value);
							
							try {
								$this->success = "Poi created!";
								$request->send();
							} catch (Exception $e) {
								$this->error = "Poi not created!";
								debug("Err: Poi not insered!");
							}
								
						}
					}
				}
			} else {
				debug("Add POI");
				// ADD SINGLE POI
				$request = new Request("POIRequestHandler", CREATE);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("user", $_SESSION["user"]->id);
				$request->addArgument("accessToken", $_SESSION["accessToken"]);

				$request->addArgument("type", $_POST['type']);
				$request->addArgument("longitude", $_POST['longitude']);
				$request->addArgument("latitude", $_POST['latitude']);

				$value = '{'.
					'"longitude" : "'. $_POST['longitude'] .'",' .
					'"latitude" : "'.  $_POST['latitude'] .'",' .
					'"title" : "'. str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['title']) .'",' .
					'"description" : "'. str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['description']).'",' .
					'"icon" : "'.		$_POST['icon'] .'",' .
					'"adresse" : "'. 	str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['Adresse']).'",' .
					'"email" : "'. 		$_POST['Email'].'",' . 
					'"link" : "'. 		$_POST['Link'].'",' .
					'"idMedia" : "'. 	$_POST['IdMedia'].'"' . 
				'}';
				$request->addArgument("value", $value);
				try {
					$request->send();
					$this->success = "Poi created!";
				} catch (Exception $e) {
					$this->error = "Poi not created!";
					debug("Err: Poi not inserted!");
				}
			}
		}

		// render View
		$this->renderView("main");
	}
}

?>
