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

		if($_POST['method'] == "subscribe") {
			$subscribe = new Subscribe($this);
			$subscribe->send();
		} else if(isset($_POST['poisrc'])){
			$pois = json_decode($_POST['data']);
			foreach($pois as $poi){
		
				$request = new Request("POIRequestHandler", CREATE);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("user", $_SESSION["user"]->id);
				$request->addArgument("accessToken", $_SESSION["accessToken"]);
		
				// Publish CARF POIs
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
		} else if(isset($_POST['addPOI'])){
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
		
		// render View
		$this->renderView("main");
	}


}
?>