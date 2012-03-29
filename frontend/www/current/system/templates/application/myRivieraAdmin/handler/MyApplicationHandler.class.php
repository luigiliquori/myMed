<?php 
require_once 'lib/dasp/request/Request.class.php';

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
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
		if(isset($_POST['poisrc'])){
			$pois = json_decode($_POST['data']);
			foreach($pois as $poi){
				
				$request = new Request("POIRequestHandler", CREATE);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("user", $_SESSION["user"]->id);
				$request->addArgument("accessToken", $_SESSION["accessToken"]);
				
				// CARF POIs
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
		} else if(isset($_POST['longitude']) && isset($_POST['latitude']) && isset($_POST['radius'])){
			$request = new Request("POIRequestHandler", READ);
			$request->addArgument("application", APPLICATION_NAME);
			$request->addArgument("type", "Commerces");
			$request->addArgument("longitude", $_POST['longitude']);
			$request->addArgument("latitude", $_POST['latitude']);
			$request->addArgument("radius", $_POST['radius']);
			$request->addArgument("accessToken", $_SESSION["accessToken"]);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status == 200) {
				echo '<script type="text/javascript">alert(\'' . json_encode($responseObject->data) . '\');</script>';
			}
			
// 			$request = new Request("POIRequestHandler", READ);
// 			$request->addArgument("application", APPLICATION_NAME);
// 			$request->addArgument("type", "carf");
// 			$request->addArgument("longitude", $_POST['longitude']);
// 			$request->addArgument("latitude", $_POST['latitude']);
// 			$request->addArgument("radius", $_POST['radius']);
// 			$request->addArgument("accessToken", $_SESSION["accessToken"]);
				
// 			$responsejSon = $request->send();
// 			$responseObject = json_decode($responsejSon);
				
// 			if($responseObject->status == 200) {
// 				echo '<script type="text/javascript">alert(\'' . json_encode($responseObject->data) . '\');</script>';
// 			}
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