<?php

/**
 * This class will verify, before handling any request, that the user is authenticated.
 * Requests that need an authenticated user should inhérit from this.
 * 
 */
class AuthenticatedController extends AbstractController implements IReputationMapper {

	/** The complete user */
	public $user;
	public static $bootstrapApplication = array("myEurope", "myRiviera", "myFSA", "myMemory", "myBen", "myEuroCIN");
	
	/**
	 * Handle the request.
	 * If the user is not authenticated, it redirects to the login page.
	 * @see IRequestHandler::handleRequest()
	 */
	public function handleRequest() {
		
		// Check for user in session
		if ( !isset($_SESSION['user']) ) {
			$this->redirectTo("login", $_REQUEST);
		}
	}
	
	

	/** Default fallback method called after an access denied, an error...*/
	public function error($arguments) {
		debug('>>>>>>>>>> error access denied!');
		debug_r($arguments);
		// Should be overridden for controller that use the "method" parameter
	}
	
	
	
	
	
}