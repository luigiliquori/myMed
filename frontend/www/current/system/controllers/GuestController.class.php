<?php

/**
 * This class will verify, before handling any request, that the user is authenticated.
 * Requests that need an authenticated user should inhérit from this.
 * 
 */
class GuestController extends AbstractController {
	
	/**
	 * Handle the request.
	 * If the user is not authenticated, it redirects to the login page.
	 * @see IRequestHandler::handleRequest()
	 */
	public function handleRequest() {
		
		// Check for user in session
		if ( !isset($_SESSION['user']) ) {
			/*debug(':: '.$_SERVER['REQUEST_URI']);
			//if we request a page, but are not connected, store it and put it back in loginController
			if (!strcontain($_SERVER['REQUEST_URI'], "action=login")){
				$_SESSION['redirect'] = substr($_SERVER['REQUEST_URI'], strlen('/application/'.APPLICATION_NAME.'/'));
				debug('saved '.$_SESSION['redirect']);
			}	*/
			
			// Redirect to "showLogin" view
			
			/* Guest access provided */
			$id = rand(100000, 999999);
			$user = (object) array('id'=>'MYMED_'.$id, 'name'=>'user'.$id);
			$_SESSION['user'] = insertUser($user, null, true);
			$_SESSION['acl'] = array('defaultMethod', 'read');
			$_SESSION['user']->is_guest = 1;
			//$this->redirectTo("login", $_REQUEST);
			
			debug('ergerg');
			
		}
	}
	
	public function read() {
		$this->redirectTo("main", $_REQUEST);
	}

	/** Default fallback method called after an access denied, an error...*/
	public function error($arguments) {
		debug('>>>>>>>>>> error access denied!');
		debug_r($arguments);
		// Should be overridden for controller that use the "method" parameter
	}
	
	
	
	
	
}