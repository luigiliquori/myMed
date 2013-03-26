<?php

// TODO: Should be a common controller in /system/controllers/
class LogoutController extends AbstractController {

	/**
	 * Logout an User by destroying the frontend AND backend session
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest() {
	
			debug("Logout !");
		
			//if (!isset($_SESSION['userFromExternalAuth'])) {	
				debug("DELETE BACKEND SESSION");
				$request = new Requestv2("v2/SessionRequestHandler", DELETE);
				if(isset($_SESSION['user']->session))
					$request->addArgument("accessToken", $_SESSION['user']->session);
				else if(isset($_SESSION['accessToken']))
					$request->addArgument("accessToken", $_SESSION['accessToken']);
				
				$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);
	
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
					
				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				}
			//}
			// DELETE FRONTEND SESSION
			session_destroy();
			
			// Redirect to login
			$this->redirectTo("main");	
	}

}
?>
