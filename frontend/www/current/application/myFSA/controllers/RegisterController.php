<?php 

class RegisterController extends AbstractController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST and send a confirmation-email.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 
		
		// First stage of registration : we receive a POST with all the informations of the user
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			// Preconditions TODO : i18n of error messages
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "Mot de passe != confirmation";
			} else if( empty($_POST['password']) ){
				$this->error = "Le mot de passe ne peut pas être vide.";
			} else if( empty($_POST['email']) ){
				$this->error = "L'email ne peut pas être vide.";
			} else if(!$_POST['checkCondition']){
				$this->error = "Vous devez accepter les conditions d'utilisation.";
			}
			
			// Error to show => show the register view
			if (!empty($this->error)) {
				$this->renderView("register");
			}
			
			// Create the new user
			$mUserBean = new MUserBean();
			$mUserBean->id = "MYMED_" . $_POST["email"];
			$mUserBean->firstName = $_POST["prenom"];
			$mUserBean->lastName = $_POST["nom"];
			$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
			$mUserBean->email = $_POST["email"];
			$mUserBean->login = $_POST["email"];
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
			$request = new Request("AuthenticationRequestHandler", CREATE);
			$request->addArgument("authentication", json_encode($mAuthenticationBean));
			$request->addArgument("user", json_encode($mUserBean));
			$request->addArgument("application", APPLICATION_NAME);
			
			// force to delete existing accessToken
			unset($_SESSION['accessToken']);
			
			// Sending request
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);

			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
				$this->renderView("register");
			} else {
				$this->success = "Félicitation, Un email de confirmation vient de vous être envoyé!";
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
			$this->error = _("Erreur interne d'enregistrement");
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
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $accessToken);
		
		// Sending request
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		// In case of errors...
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
		} else {
			$this->success = _("Votre compte à bien été validé. Vous pouvez vous loguer à présent");
		}
		$this->renderView("login");
		
	}

}
?>