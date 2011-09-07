<?php 
require_once 'system/request/Request.class.php';
require_once 'system/request/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyCARFHandler implements IRequestHandler {
	
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
		if(isset($_GET['publish'])) {
			$request = new Request("DHTRequestHandler", CREATE);
			$request->addArgument("key", $_GET['start'].$_GET['end'].$_GET['date']);
			$request->addArgument("value", $_SESSION['user']->id);
			$response = $request->send();
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
			} else {
				$this->success = "Request sent!";
			}
		} else if(isset($_GET['subscribe'])) {
			$request = new Request("DHTRequestHandler", READ);
			$request->addArgument("key", $_GET['start'].$_GET['end'].$_GET['date']);
			$response = $request->send();
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
		}
	}
	
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
}
?>