<?php

// TODO: Should be a common controller in /system/controllers/
class LoginController extends AbstractController {

	/**
	 * Create a session if the logins are correct
	 * Returns nothing but populate $_SESSION['accessToken'] in case of success.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {
		
		/* Typical login : we received a POST with login and password */
		if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
		
			// Get arguments 
			$login	= $_POST['login'];
			$pass	= hash("sha512", $_POST['password']);
		
			// Login and password should not be empty
			if( empty($login) ){
				// TODO i18n
				$this->error = "Le champ email ne peut pas être vide";
				$this->renderView("login");
			} else if( empty($pass) ){
				// TODO i18n
				$this->error = "Le champ mot de passe ne peut pas être vide!";
				$this->renderView("login");
			}
					
			// Building the Authentication request
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $login);
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
				$this->error = "Mot de pass incorrect! <a href='?action=resetPassword&login=" . urlencode($login) . "' rel='external'>Pertes identifiants?</a>";
					
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
				
				// Set user into $_SESSION
				$this->getUserFromSession();
				
				// FIX THE LOGIN TO LOWER CASE IF IT'S NEEDED
				if (strtolower($login) != login) {
					// create the authentication
					$mAuthenticationBean = new MAuthenticationBean();
					$mAuthenticationBean->login =  strtolower($login); // LOWER CASE LOGIN
					$mAuthenticationBean->user = $_SESSION['user']->id;
					$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
					
					$request = new Request("AuthenticationRequestHandler", CREATE);
					$request->addArgument("authentication", json_encode($mAuthenticationBean));
					$request->addArgument("user", json_encode($_SESSION['user']));
					$request->addArgument("application", APPLICATION_NAME);
						
					// Sending request => Force to create a new account
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
				}
				
				// Redirect to main page
				$this->redirectTo("main");
			}
			
		} else { // Not a POST request : Simply show the login form 
			
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
		$request = new Request("SessionRequestHandler", READ);
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
			$_SESSION['user'] = json_decode($responseObject->data->user);
			if( !isset($_SESSION['friends']) ){
				$_SESSION['friends'] = array();
			}
		}
	}
}
?>