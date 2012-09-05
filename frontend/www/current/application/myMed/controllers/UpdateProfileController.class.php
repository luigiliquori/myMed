<?php 

// TODO: Should be a common controller in /system/controllers/
class UpdateProfileController extends AbstractController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST and send a confirmation-email.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 
		
		// Preconditions
		if($_POST['password'] != $_POST['confirm']){
			$this->error = "FAIL: password != confirmation";
		} else if($_POST['password'] == ""){
			$this->error = "FAIL: password cannot be empty!";
		} else if($_POST['email'] == ""){
			$this->error = "FAIL: email cannot be empty!";
		}
		
		// Error to show => show the register view
		if (!empty($this->error)) {
			$this->renderView("register");
			return;
		}
		
		// update the authentication
		$mAuthenticationBean = new MAuthenticationBean();
		$mAuthenticationBean->login =  $_SESSION['user']->email;
		$mAuthenticationBean->user = $_SESSION['user']->id;
		$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
		
		$request = new Request("AuthenticationRequestHandler", UPDATE);
		$request->addArgument("authentication", json_encode($mAuthenticationBean));
		
		$request->addArgument("oldLogin", $_SESSION['user']->email);
		$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));
		
		$responsejSon = $request->send();
		$responseObject1 = json_decode($responsejSon);
		
		if($responseObject1->status != 200) {
			$this->error = $responseObject1->description;
		}
		
		// update the profile
		
		
		/*$mUserBean = new MUserBean();
		$mUserBean->id = $_SESSION['user']->id;
		$mUserBean->firstName = $_POST["prenom"];
		$mUserBean->lastName = $_POST["nom"];
		$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
		$mUserBean->email = $_POST["email"];
		$mUserBean->login = $_POST["email"];
		$mUserBean->birthday = $_POST["birthday"];
		$mUserBean->profilePicture = $_POST["thumbnail"];
		
		// keep the session opened 
		$mUserBean->socialNetworkName = $_SESSION['user']->socialNetworkName;
		$mUserBean->SocialNetworkID = $_SESSION['user']->SocialNetworkID;
		$mUserBean->SocialNetworkID = $_SESSION['accessToken'];*/
		
		$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
		$_POST['login'] = $_POST["email"];
		unset($_POST['oldPassword']);
		unset($_POST['password']);
		unset($_POST['confirm']);
		
		$request = new Requestv2("v2/ProfileRequestHandler", UPDATE , array("user"=>json_encode($_POST)));
		$responsejSon = $request->send();
		$responseObject2 = json_decode($responsejSon);

		if($responseObject2->status != 200) {
			$this->error = $responseObject2->description;
		} else{
			$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $_POST);
		}
		
		$this->redirectTo("Main", null, "#profile");
		
	}

}
?>