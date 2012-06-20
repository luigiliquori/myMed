<?php

class LogoutController extends AbstractController {

	/**
	 * Logout an User by destroying the frontend AND backend session
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest() {
	
			debug("Logout !");
		
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
			
			// Redirect to login
			$this->redirectTo("login");	
	}

}
?>
