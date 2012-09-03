<?php 
require_once '../../lib/dasp/request/Subscribe.class.php';
require_once '../../lib/dasp/request/Request.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyApplicationHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
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
	}
	
	/* --------------------------------------------------------- */
	/* Getter&Setter */
	/* --------------------------------------------------------- */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*void*/ function setError(/*String*/ $error){
		return $this->error = $error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	public /*void*/ function setSuccess(/*String*/ $success){
		return $this->success = $success;
	}
	
}
?>