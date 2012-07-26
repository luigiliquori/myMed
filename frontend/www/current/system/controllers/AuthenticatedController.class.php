<?php

/**
 * 
 * This class will verify, before handling any request, that the user is authenticated.
 * Requests that need an authenticated user should inhérit from this.
 * 
 * @author David Da Silva
 *
 */
class AuthenticatedController extends AbstractController {

	/** The complete user */
	public $User;
	
	/**
	 * Handle the request.
	 * If the user is not authenticated, it redirects to the login page.
	 * @see IRequestHandler::handleRequest()
	 */
	public /* String */ function handleRequest() {
		
		// Check for user in session
		if( !isset($_SESSION['user']) ) {
			// Redirect to "showLogin" view 
			$this->redirectTo("login");
		} else {
			$this->user = $_SESSION['user'];
		}
	}
}