<?php 

define('EXTENDED_PROFILE_PREFIX' , 'extended_profile_');
define('STORE_PREFIX' , 'store_');

// TODO: Should be a common controller in /system/controllers/
class ProfileController extends AuthenticatedController {
	

	public function update() {
		$this->renderView('updateProfile');
	}
	
	public function defaultMethod() {
		$this->renderView("profile");
	}
	
	public function handleRequest() { 
		
		parent::handleRequest();
		
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // UPDATE PROFILE
			$_POST["email"] = strtolower(trim($_POST["email"]));
			if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false){
				$this->error = _("Email not valid");
				$this->renderView("updateProfile");
			}
			
			if (isset($_POST['passwordConfirm'])){ // no email, no login
				$mAuthenticationBean = new MAuthenticationBean();
				$mAuthenticationBean->login = $_SESSION['user']->id;
				$mAuthenticationBean->user = $_SESSION['user']->id;
				$mAuthenticationBean->password = hash('sha512', $_POST["passwordConfirm"]);
				unset($_POST['passwordConfirm']);
				$request = new Requestv2("v2/AuthenticationRequestHandler", UPDATE,
					array("authentication"=>json_encode($mAuthenticationBean)));
				try {
					$res = $request->send();
				} catch (Exception $e){
					$this->setError($res->description);
					$this->renderView("profile");
				}
				
				/*
				 * @TODO check if there is an account with MYMED_$_POST["email"]
				 * if yes prompt the user if he wants to merge his accounts
				 * ask for MYMED's acount password, merge MYMED's profile, update old profile with "merged": MYMED profile id
				 */ 
				
			}
			
			$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
			
			debug_r($_POST);
			$request = new Requestv2("v2/ProfileRequestHandler", UPDATE, array("user"=>json_encode($_POST))
			);
			
			try {
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
		
				if($responseObject->status != 200) {
					throw new Exception($responseObject->description);
				} else{
					$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $_POST);
					$this->success = _("Your profile has been successfully updated!");
				}
				
			} catch (Exception $e){
				$this->error = $e->getMessage();
				$this->renderView("updateProfile");
			}
		}

	}

}
?>