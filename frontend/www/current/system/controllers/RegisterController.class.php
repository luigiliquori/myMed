<?php 

// TODO: Should be a common controller in /system/controllers/
class RegisterController extends AbstractController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST and send a confirmation-email.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 
		if(isset($_GET['method'])){
			if($_GET['method']=='showRegisterView'){
				$this->renderView("register");
			}
		}
		
		// First stage of registration : we receive a POST with all the informations of the user
		else if ($_SERVER['REQUEST_METHOD'] == "POST") {
			// Preconditions TODO : i18n of error messages
			if( empty($_POST['email']) ){
				$this->error = _("Email field can't be empty");
			} else if($_POST['password'] != $_POST['confirm']){
				$this->error = _("Passwords do not match");
			} else if( empty($_POST['password']) ){
				$this->error = _("Password field can't be empty");
			} else if(!$_POST['checkCondition']){
				$this->error = _("You must accept the terms of use.");
			} else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
				$this->error = _("Email not valid");
			}
			
			// Error to show => show the register view
			if (!empty($this->error)) {
				$this->renderView("register");
				return;
			}
			
			// Create the new user
			$mUserBean = new MUserBean();
			$email = strtolower(trim($_POST["email"]));
			$mUserBean->id = "MYMED_" . $email;
			$mUserBean->firstName = $_POST["prenom"];
			$mUserBean->lastName = $_POST["nom"];
			$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
			$mUserBean->email = $email;
			$mUserBean->login = $email;
			$mUserBean->birthday = $_POST["birthday"];
			$mUserBean->profilePicture = $_POST["thumbnail"];
			
			// create the authentication
			$mAuthenticationBean = new MAuthenticationBean();
			$mAuthenticationBean->login =  $mUserBean->login;
			$mAuthenticationBean->user = $mUserBean->id;
			$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
			
			/*
			 * Building the Authentication request to register the new account
			 * This will create a temporary profile, waiting for e-mail confirmation
			 */
			$request = new Requestv2("v2/AuthenticationRequestHandler", CREATE);
			$request->addArgument("authentication", json_encode($mAuthenticationBean));
			$request->addArgument("user", json_encode($mUserBean));
			// TODO Sostituito APPLICATION_NAME con "myMed" 
			// $request->addArgument("application", APPLICATION_NAME);
			$request->addArgument("application", "myMed"); 
			
			// force to delete existing accessToken
			unset($_SESSION['accessToken']);
			
			// Sending request
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			debug("CREATE AUTHENTIFICATION REQUEST");
			debug_r($responsejSon);

			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
				$this->renderView("register");
			} else {
				$this->success = _("An email has been sent to you!");
				$this->renderView("login");
			}
		}
		
		// Case where the user click the link on the e-mail to confirm registration 
		else if (isset($_GET['registration']) AND isset($_GET['accessToken'])) {
			
			// Force to delete existing accessToken
			unset($_SESSION['accessToken']);
			
			// Check the registration, and renders the login page
			$this->confirmRegistration($_GET['accessToken']);
		 
		} else {
			$this->error = _("Internal error of registration");
			$this->renderView("register");
		}
		// Render the register view 
		$this->renderView("register");
		
	}
	
	
	/**
	 * Try to confirm the registration of a user by using the given accessToken 
	 * @param String $accessToken
	 */
	public /*String*/ function confirmRegistration($accessToken) {
		
		// Building Authentication request
		// This will confirm the temporary profile (if it exists)
		$request = new Requestv2("v2/AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $accessToken);
		
		// Sending request
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		debug("CREATE AUTHENTIFICATION REQUEST");
		debug_r($responsejSon);
		
		// In case of errors...
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
		} else {
			$this->success = "Your account has been validated. You can connect now";
		}
		$this->renderView("login");
		
	}

}
?>