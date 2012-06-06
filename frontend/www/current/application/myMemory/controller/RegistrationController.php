<?php 
require_once '../../../lib/dasp/request/Request.class.php';
require_once '../../../lib/dasp/beans/MUserBean.class.php';
require_once '../../../lib/dasp/beans/MAuthenticationBean.class.php';

class RegistrationController implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	
	/**
	 * This will create a temporary Profile with the informations submited by POST and send a confirmation-email.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 
		
		/*
		 * First stage of registration : we receive a POST with all the informations of the user
		 */
		if(isset($_POST['inscription'])) {
			
			// Preconditions
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "ERR: Mot de passe != confirmation";
				return;
			} else if( empty($_POST['password']) ){
				$this->error = "ERR: le mot de passe ne peut pas être vide.";
				return;
			} else if( empty($_POST['email']) ){
				$this->error = "ERR: l'email ne peut pas être vide.";
				return;
			} else if(!$_POST['checkCondition']){
				$this->error = "ERR: Vous devez accepter les conditions d'utilisation.";
				return;
			}
			
			// create the new user
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
				$_SESSION['error'] = $responseObject->description;
			} else {
				$this->success = "Félicitation, Un email de confirmation vient de vous être envoyé!";
			}
		}
		/*
		 * Case where the user click the link on the e-mail to confirm registration
		 */
		else if ( isset($_GET['registration']) AND isset($_GET['accessToken']) )
		{
			// force to delete existing accessToken
			unset($_SESSION['accessToken']);
			
			$this->confirmRegistration($_GET['accessToken']);
		 
		 /*
		 else if (isset($_GET['inscription'])) {
			$this->success = "Votre compte à bien été validé!";*/
		} 
	}
	
	
	/**
	 * 
	 * Try to confirm the registration of a user by using the given accessToken 
	 * @param String $accessToken
	 */
	public /*String*/ function confirmRegistration($accessToken) {
		
		/*
		 *  Building Authentication request
		 *  This will confirm the temporary profile (if it exists)
		 */
		$request = new Request("AuthenticationRequestHandler", CREATE);
		$request->addArgument("accessToken", $accessToken);
		
		// Sending request
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		// In case of errors...
		if($responseObject->status != 200) {
			$_SESSION['error'] = $responseObject->description;
		} else {
			$this->success = "Votre compte à bien été validé!";
		}
		
	}
	
	
	
	
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>