<?php 
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MenuHandler implements IRequestHandler {
	
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
		if(isset($_POST['disconnect'])) {
			// DELETE BACKEND SESSION
			$request = new Request("SessionRequestHandler", DELETE);
			$request->addArgument("userID", $_SESSION['user']->id);
			$response = $request->send();
			$check = json_decode($response);
			if(isset($check->error)) {
				$this->error = $check->error->message;
			} else {
				// DELETE FRONTEND SESSION
				session_destroy();
				if (isset($_SESSION['wrapper'])){	
					header("Refresh:0;url=".$_SESSION['wrapper']->getLogoutUrl());
				} else {
					header("Refresh:0;url=".$_SERVER['PHP_SELF']);
				}
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