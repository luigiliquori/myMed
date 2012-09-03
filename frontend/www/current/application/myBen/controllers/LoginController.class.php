<?php

// TODO: Should be a common controller in /system/controllers/
class LoginController extends GuestOrUserController {

	/** Default action : show the login form */
	public function defaultMethod() {
		$this->renderView("login");
	}
	
	/** Login form submitted */
	public function doLogin() {

		// Get arguments 
		$login	= $_POST['login'];
		$pass	= hash("sha512", $_POST['password']);
	
		// Login and password should not be empty
		if( empty($login) ){
			// TODO i18n
			$this->error = "eMail cannot be empty!";
			$this->renderView("login");
		} else if( empty($pass) ){
			// TODO i18n
			$this->error = "Password cannot be empty!";
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
	
		// In case of errors
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
			$this->renderView("login");
		}
		
		// Everything went fine, we have now an accessToken in the session
		$this->setToken($responseObject->dataObject->accessToken);
			
		// Set user into $_SESSION
		$this->getUserFromSession();
		
		// Get the extended profile
		$this->setExtendedProfile(ExtendedProfileRequired::getExtendedProfile($this->user->id));
		
		// Benevole ? => Register to subscriptions
		// XXX Bad : Should be done once only
		if ($this->extendedProfile instanceof ProfileBenevole && (is_true($this->extendedProfile->subscribe))) {
			$this->extendedProfile->getAnnonceQuery()->subscribe();
		} elseif ($this->extendedProfile instanceof ProfileAssociation) {
			
			// Suscribe to association validation
			$query = new ValidationAssociation();
			$query->associationID = $this->user->id;
			$query->subscribe();
		}
		
		// Redirect to main page
		$this->redirectTo("main");

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
			$this->setUser(json_decode($responseObject->data->user));
			
			if( !isset($_SESSION['friends']) ){
				$_SESSION['friends'] = array();
			}
		}
	}
}
?>