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
								
							// needed parameters
							if(isset($feature->geometry)) {
								// CARF POIs
								$longitude = $feature->geometry->coordinates[0];
								$latitude  = $feature->geometry->coordinates[1];
							} else { // Students POIs
								$longitude = $feature->properties->Longitude;
								$latitude  = $feature->properties->Latitude;
							}
							$latitude = str_replace(',', '.', $latitude);
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
							
							echo $value . "<br />" . "<br />";
							
							$request->addArgument("value", $value);
							
							try {
								$request->send();
							} catch (Exception $e) {
								debug("Err: Poi not insered!");
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

				$value = '{'.
					'"longitude" : "'. $_POST['longitude'] .'",' .
					'"latitude" : "'.  $_POST['latitude'] .'",' .
					'"title" : "'. str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['title']) .'",' .
					'"description" : "'. str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['description']).'",' .
					'"icon" : "'.		$_POST['icon'] .'",' .
					'"Adresse" : "'. 	str_replace(array("\r", "\r\n", "\n", "\""), '',$_POST['Adresse']).'",' .
					'"Email" : "'. 		$_POST['Email'].'",' . 
					'"Link" : "'. 		$_POST['Link'].'",' .
					'"IdMedia" : "'. 	$_POST['IdMedia'].'"' . 
				'}';
				$request->addArgument("value", $value);
				$request->send();
			}
		}

		// render View
		$this->renderView("main");
	}
}

?>