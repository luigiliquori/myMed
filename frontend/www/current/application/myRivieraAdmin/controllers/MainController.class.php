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

		} else if(isset($_POST['addPOI'])){
				
			// ADD GROUP OF POIs
			if(isset($_POST['data'])) {
				
				echo $_POST['data'] . "<br />";
				
				$pois = json_decode($_POST['data']);
				
				echo "pois = " . $pois . "<br />";
				
				foreach($pois as $poi){
					
					echo $poi . "<br />";

					$request = new Request("POIRequestHandler", CREATE);
					$request->addArgument("application", APPLICATION_NAME);
					$request->addArgument("user", $_SESSION["user"]->id);
					$request->addArgument("accessToken", $_SESSION["accessToken"]);

					// CARF POI
					if(isset($poi->features)) {
						foreach ($poi->features as $feature) {
							$request->addArgument("type", preg_replace("/_.+$/", "", $feature->properties->Type));
							$request->addArgument("longitude", $feature->geometry->coordinates[0]);
							$request->addArgument("latitude", $feature->geometry->coordinates[1]);
							$value = '{
								"longitude" : "'. $feature->geometry->coordinates[0] .'", 
								"latitude" : "'. $feature->geometry->coordinates[1] .'", 
								"title" : "'. $feature->properties->Nom .'", 
								"description" : "'. $feature->properties->Description .'", 
								"icon" : ""
								}';
							$request->addArgument("value", $value);
							$request->send();
						}
					}

					// PAYS PAILLONS POI
					if(isset($poi->Type)) {
						
						echo "Type: " . $poi->Type;
						
						$request->addArgument("type", $poi->Type);
						$request->addArgument("longitude", $poi->Longitude);
						$request->addArgument("latitude", $poi->Latitude);
						$value = '{
													"longitude" : "'. $poi->Longitude .'", 
													"latitude" : "'. $poi->Latitude .'", 
													"title" : "'. $poi->Nom .'", 
													"description" : "'. $poi->Description .'", 
													"SousType" : "'. $poi->SousType .'", 
													"Adresse" : "'. $poi->Adresse .'", 
													"E-mail" : "'. $poi->E-mail .'", 
													"Link" : "'. $poi->Link .'", 
													"IdMedia" : "'. $poi->IdMedia .'", 
													"ComUrbaine" : "'. $poi->ComUrbaine .'", 
													"Altitude" : "'. $poi->Altitude .'", 
													"icon" : ""
													}';
						echo "Longitude" . $poi->Longitude . "<br />";
						echo "Latitude" . $poi->Latitude . "<br />";
						echo "$value" . $value . "<br />";

						$request->addArgument("value", $value);
						$request->send();
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