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

		// Set the flag
		$_SESSION["launchpad"] = true;

		// SUBSCRIBE
		if($_POST['method'] == "subscribe") {
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
							
							if(isset($feature->geometry)) { // CARF POIs
								$longitude = $feature->geometry->coordinates[0];
								$latitude  = $feature->geometry->coordinates[1];
							} else { // Students POIs
								$longitude = $feature->properties->Longitude;
								$latitude  = $feature->properties->Latitude;
							}
							$longitude = str_replace(',', '.', $longitude);
							$latitude = str_replace(',', '.', $latitude);
							
							$request->addArgument("longitude", $longitude);
							$request->addArgument("latitude", $latitude);
							$request->addArgument("type", $feature->properties->Type);
							
							$value = '{
								"longitude" : "'. 	$longitude .'", 
								"latitude" : "'. 	$latitude .'", 
								"title" : "'. 		$feature->properties->Nom .'", 
								"description" : "'. $feature->properties->Description . '",
								"icon" : ""';
							
							if(isset($feature->properties->SousType)){ // Students POIs
								$value .= ',
								"SousType" : "'.	$feature->properties->SousType.'", 
								"Adresse" : "'. 	$feature->properties->Adresse.'", 
								"E-mail" : "'. 		$feature->properties->Email.'", 
								"Link" : "'. 		$feature->properties->Link.'", 
								"IdMedia" : "'. 	$feature->properties->IdMedia.'", 
								"ComUrbaine" : "'.	$feature->properties->ComUrbaine.'", 
								"Altitude" : "'.	$feature->properties->Altitude.'" 
								}';
							} else {
								$value .= '}';
							}
							
							$request->addArgument("value", $value);
							
							try {
								$request->send();
							} catch (Exception $e) {
							}
							
						}
					}
				}
			} else {

				// ADD SINGLE POI
				$request = new Request("POIRequestHandler", CREATE);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("user", $_SESSION["user"]->id);
				$request->addArgument("accessToken", $_SESSION["accessToken"]);

				$request->addArgument("type", $_POST['type']);
				$request->addArgument("longitude", $_POST['longitude']);
				$request->addArgument("latitude", $_POST['latitude']);
				$value = '{
					"longitude" : "'. $_POST['longitude'] .'", 
					"latitude" : "'. $_POST['latitude'] .'", 
					"title" : "'. $_POST['title'] .'", 
					"description" : "'. $_POST['description'] .'", 
					"icon" : ""
				}';
				$request->addArgument("value", $value);
				$request->send();
			}
		}
		
		// render View
		$this->renderView("main");
	}
}
	
?>