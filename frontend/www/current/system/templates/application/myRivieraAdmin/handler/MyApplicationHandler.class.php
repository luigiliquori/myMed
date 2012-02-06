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
				$request->addArgument("value", $_POST['data']);
				$request->addArgument("accessToken", $_SESSION["accessToken"]);
				if($_POST['poisrc'] == "mymed"){
					$request->addArgument("type", "mymed");
					$request->addArgument("latitude", $poi->latitude);
					$request->addArgument("longitude", $poi->longitude);
				} else {
					
				}
				$request->send();
			}
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