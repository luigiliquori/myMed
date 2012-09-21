<?php

/**
 * This class will verify, before handling any request, that the user is authenticated.
 * Requests that need an authenticated user should inhérit from this.
 * 
 * @author David Da Silva
 */
class AuthenticatedController extends AbstractController {

	/** The complete user */
	public $user;
	
	/**
	 * Handle the request.
	 * If the user is not authenticated, it redirects to the login page.
	 * @see IRequestHandler::handleRequest()
	 */
	public /* String */ function handleRequest() {
		
		// Check for user in session
		if( !isset($_SESSION['user']) ) {
			/*debug(':: '.$_SERVER['REQUEST_URI']);
			//if we request a page, but are not connected, store it and put it back in loginController
			if (!strcontain($_SERVER['REQUEST_URI'], "action=login")){
				$_SESSION['redirect'] = substr($_SERVER['REQUEST_URI'], strlen('/application/'.APPLICATION_NAME.'/'));
				debug('saved '.$_SESSION['redirect']);
			}	*/			
			
			// Redirect to "showLogin" view
			$this->redirectTo("login", $_REQUEST);
		} else {
			$this->user = $_SESSION['user'];
		}
	}
	
	public function reputation( $producer=null, $id=null ){
		$rep =  new ReputationSimple( $producer, $id, APPLICATION_NAME);
		$res = $rep->send();
		if($res->status != 200) {
			throw new Exception($res->description);
		} else {
			return formatReputation($res->dataObject->reputation);
		}
	}
}