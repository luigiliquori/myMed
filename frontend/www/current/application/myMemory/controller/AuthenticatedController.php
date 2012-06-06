<?php
/*
 * IMPORT ALL THE THINGS!
 */
require_once '../../../lib/dasp/request/Request.class.php';
require_once '../../../lib/dasp/beans/MUserBean.class.php';
require_once '../../../lib/dasp/beans/MAuthenticationBean.class.php';
//require_once '../../../lib/socialNetworkAPIs/SocialNetworkConnection.class.php';

/**
 * 
 * This class will verify, before handling any request, that the user is authenticated.
 * Requests that need an authenticated user should inhérit from this.
 * 
 * @author David Da Silva
 *
 */
class AuthenticatedController implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	
	public function __construct(){
		
		$this->error	= false;
		$this->success	= false;	
	}
	
	
	/**
	 * Handle the request.
	 * If the user is not authenticated, it redirects to the login page.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {
		
		
		// Check for user in session
		if( !isset($_SESSION['user']) )
		{
			//TODO : create this controller.
			header("Refresh:0;url=/index.php?method=login");
		}
	}
	
	
	
	
	
	
	
	/*
	 * Getters
	 */
	

	/**
	 * Get the error state
	 * @return String
	 */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	/**
	 * Get the success state
	 * @return String
	 */
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	
}