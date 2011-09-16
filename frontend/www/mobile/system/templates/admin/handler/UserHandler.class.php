<?php 
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class UserHandler implements IRequestHandler {
	
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
		if(isset($_POST['userRead'])) {
			// DELETE BACKEND SESSION
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id", $_POST['id']);
			$response = $request->send();
			$check = json_decode($response);
			if(isset($check->error)) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
		} else if(isset($_POST['userDelete'])) {
			// DELETE BACKEND SESSION
			$request = new Request("ProfileRequestHandler", DELETE);
			$request->addArgument("id", $_POST['id']);
			$response = $request->send();
			$check = json_decode($response);
			if(isset($check->error)) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
		} else if(isset($_POST['userUpdate'])) {
			// DELETE BACKEND SESSION
			$request = new Request("ProfileRequestHandler", UPDATE);
			$request->addArgument("user", $_POST['user']);
			$response = $request->send();
			$check = json_decode($response);
			if(isset($check->error)) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
		}
		//echo "<script type='text/javascript'>alert('test');</script>";
	}
	
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>