<?php

// TODO: Should be a common controller in /system/controllers/
class LoginController extends AbstractController {

	/**
	 * Create a session if the logins are correct
	 * Returns nothing but populate $_SESSION['accessToken'] in case of success.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {

		/** authed by social networks apis*/
		if (isset($_SESSION['userFromExternalAuth'])) {

			$token = isset($_SESSION['accessToken'])?$_SESSION['accessToken']:null;
			debug_r($_SESSION['user']);
			$_SESSION['user'] = insertUser($_SESSION['userFromExternalAuth'], $token);
			$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create');
			$_SESSION['user']->is_guest = 0;
			
			// Redirect to main page
			$this->redirectTo("main");
		
		}
		
		/* Typical login : we received a POST with login and password */
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Get arguments 
			$login	= trim($_POST['login']);
			$pass	= hash("sha512", $_POST['password']);
		
			// Login and password should not be empty
			if( empty($login) ){
				// TODO i18n
				$this->error = _("Login field can't be empty");;
				$this->renderView("login");
			} else if( empty($pass) ){
				// TODO i18n
				$this->error = _("Password field can't be empty");
				$this->renderView("login");
			}
					
			// Building the Authentication request
			$request = new Requestv2("v2/AuthenticationRequestHandler");
			$request->addArgument("login", $login); // must strtolower here first and if error check with <
			$request->addArgument("password", $pass);
			
			// Argument code filled by Request
			
			// Sending request
			$responsejSon = $request->send();
			
			$responseObject = json_decode($responsejSon);
			
			// the login doesn't exist
			if($responseObject->status == 404) {
				// try in lowercase
				$request->addArgument("login", strtolower($login));
				
				// Sending request
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
			}
			
			// wrong password
			if($responseObject->status == 403) {
				$this->error = _("Incorrect password")." <a href='?action=resetPassword&login=" . urlencode($login) . "' rel='external'>"._("Loss of identifiers?")."</a>";
					
				// Show the login form
				$this->renderView("login");
			
			} else if($responseObject->status != 200) {

				// Save the error
				$this->error = $responseObject->description;
				//debug("error");	
					
				// Show the login form
				$this->renderView("login");
				
			} else {
				
				// Everything went fine, we have now an accessToken in the session
				$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
				
				// FIX THE LOGIN TO LOWER CASE IF IT'S NEEDED
				if (strtolower($login) != $login) {
					// create the authentication
					$mAuthenticationBean = new MAuthenticationBean();
					$mAuthenticationBean->login =  strtolower($login); // LOWER CASE LOGIN
					$mAuthenticationBean->user = $_SESSION['user']->id;
					$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
					
					$request = new Requestv2("v2/AuthenticationRequestHandler", CREATE);
					$request->addArgument("authentication", json_encode($mAuthenticationBean));
					$request->addArgument("user", json_encode($_SESSION['user']));
					$request->addArgument("application", APPLICATION_NAME);
						
					// Sending request => Force to create a new account
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
				}
				
				// Set user into $_SESSION
				$this->getUserFromSession();
				
				// Redirect to main page
				$this->redirectTo("main");
			}
			
		} else { // Not a POST request : 

			$this->renderView("login");
			
		}

	}

	/**
	 * Get the jSon of the user by using the Session existing (or not) in the Backend.
	 * Returns nothing but populate $_SESSION['user']
	 */
	public /*user*/ function getUserFromSession() {
		
		// Building the Session Request
		// This will check if the session exists in the backend and will return an User if it's the case.	
		$request = new Requestv2("v2/SessionRequestHandler");
		$request->addArgument("socialNetwork", "myMed");
		
		// The AccessToken is fetched from the $_SESSION
		
		// Sending request
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		// In case of errors
		if($responseObject->status != 200) {
			$_SESSION['error'] = $responseObject->description;
		} else {
			// Everything went fine, we now have an USER in our session
			$_SESSION['user'] = (object) array_map('trim', (array) $responseObject->dataObject->user);
			$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create');
			$_SESSION['user']->is_guest = 0;
			if( !isset($_SESSION['friends']) ){
				$_SESSION['friends'] = array();
			}
		}
	}
	
	
}
?>