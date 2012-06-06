<?php
require_once '../../../lib/dasp/request/Request.class.php';

class LogoutController implements IRequestHandler {

	private /*string*/ $error;
	private /*string*/ $success;


	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}

	/**
	 * Logout an User by destroying the frontend AND backend session
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest() {
	
			// DELETE BACKEND SESSION
			$request = new Request("SessionRequestHandler", DELETE);
			$request->addArgument("accessToken", $_SESSION['user']->session);
			$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			}
				
			// DELETE FRONTEND SESSION
			session_destroy();
			if (isset($_SESSION['wrapper'])){
				header("Refresh:0;url=".$_SESSION['wrapper']->getLogoutUrl());
			}
			header("Refresh:0;url=/application/" . APPLICATION_NAME);
	}

	public /*String*/ function getError(){
		return $this->error;
	}

	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>
