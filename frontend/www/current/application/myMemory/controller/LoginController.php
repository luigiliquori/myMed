<?php
require_once '../../../lib/dasp/request/Request.class.php';
//require_once '../../../lib/dasp/beans/MUserBean.class.php';
//require_once '../../../lib/dasp/beans/MAuthenticationBean.class.php';
//require_once '../../../lib/socialNetworkAPIs/SocialNetworkConnection.class.php';


class LoginController implements IRequestHandler {

	private /*string*/ $error;
	private /*string*/ $success;
	//private /*SocialNetworkConnection*/ $socialNetworkConnection;


	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		//$this->socialNetworkConnection = new SocialNetworkConnection();
	}
	
	
	/**
	 * Create a session if the logins are correct
	 * Returns nothing but populate $_SESSION['accessToken'] in case of success.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {
		
		/*
		 * Typical login : we received a POST with login and password
		 */
		if(isset($_POST['signin'])) {
		
			$login	= $_POST['login'];
			$pass	= hash("sha512", $_POST['password']);

			/*
			 * Login and password should not be empty
			 */
			if( empty($login) ){
				$this->error = "FAIL: eMail cannot be empty!";
				return;
			} else if( empty($pass) ){
				$this->error = "FAIL: password cannot be empty!";
				return;
			}
			
			
			/*
			 * Building the Authentication request
			 */
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $login);
			$request->addArgument("password", $pass);
			// argument code filled by Request
			
			// Sending request
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
		
			// In case of errors
			if($responseObject->status != 200) {
				$_SESSION['error'] = $responseObject->description;
			} else {
				// Everything went fine, we have now an accessToken in the session
				$_SESSION['accessToken'] = $responseObject->data->accessToken;
				
				/*
				 * Now we get the user from the session created by the backend.
				 */
				$this->getUserFromSession();
			}
		}

	}

	/**
	 * Get the jSon of the user by using the Session existing (or not) in the Backend.
	 * Returns nothing but populate $_SESSION['user']
	 */
	public /*user*/ function getUserFromSession() {
		
		/*
		 * Building the Session Request
		 * This will check if the session exists in the backend and will return an User if it's the case.
		 */
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
	
	
	
	/**
	 * Get the error
	 * @return String
	 */
	public /*String*/ function getError(){
		return $this->error;
	}

	/**
	 * Get the success
	 * @return String
	 */
	public /*String*/ function getSuccess(){
		return $this->success;
	}

	
}
?>