<?php

class CreateMyMedProfileController extends GuestOrUserController{
	
	public function defaultMethod() {
		$this->renderView("CreateMyMedProfile");
	}
	
	function create(){
		// Creation of a new user
		$this->_user = new MUserBean();
		$this->_user->email = $_POST['email'];
		$this->_user->id = "MYMED_" . $_POST["email"];
		$this->_user->login = $_POST["email"];
		
		if (in_request("lastName")) {
			$this->_user->lastName = $_POST["lastName"];
		}
		if (isset($_POST['lang'])) {
			$this->_user->lang = $_POST["lang"];
		}
		
		$this->_user->firstName = $_POST["firstName"];
		$this->_user->name = $this->_user->firstName . " " . $this->_user->lastName;
		$this->_user->lastConnection = 0;
		if (in_request("birthday")) {
			$this->_user->birthday = $_POST["birthday"];
		}
		
		// Create new user with authentication
		if (empty($_POST['password']) ) {
			$this->error = _("Le mot de passe est vide");
			return false;
		}
		if (empty($_POST['confirm']) ) {
			$this->error = _("Le mot de passe de confirmation est vide");
			return false;
		}
		if ($_POST['confirm'] != $_POST['password']) {
			$this->error = _("Le mot de passe et le mot de passe de confirmation sont différents");
			return false;
		}
		
		// Create authentication bean
		$mAuthenticationBean = new MAuthenticationBean();
		$mAuthenticationBean->login =  $this->_user->login;
		$mAuthenticationBean->user = $this->_user->id;
		$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
		
		$rq = new Request("AuthenticationRequestHandler", CREATE);
		$rq->addArgument("authentication", json_encode($mAuthenticationBean));
		$rq->addArgument("user", json_encode($this->_user));
		$rq->addArgument("application", APPLICATION_NAME);
		$rq->setUseAccessToken(false);
		
		
		// Send request
		$rq->setMultipart(true);
		$responseObject = json_decode($rq->send());
		
		// Error ?
		if ($responseObject->status != 200) {
			$this->error = $responseObject->description;
			return false;
		}
		
		// Everything went fine
		//return true;
		$this->renderView("main");
	}
}
?>