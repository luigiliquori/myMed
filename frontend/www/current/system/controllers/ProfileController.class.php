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
		
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			// Preconditions @TODO in javascript
			$_POST["email"] = strtolower(trim($_POST["email"]));
			if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false){
				$this->error = _("Email not valid");
				$this->renderView("updateProfile");
			}
			if(empty($_POST["password"])){
				$this->error = _("password empty");
				$this->renderView("updateProfile");
			}
			
			//check password here
			if (isset($_POST['passwordConfirm'])){
				if ($_POST['passwordConfirm'] !== $_POST['password']){
					$this->error = _("Passwords do not match");
					$this->renderView("profile");
				}
				unset($_POST['passwordConfirm']);
				$mAuthenticationBean = new MAuthenticationBean();
				$mAuthenticationBean->login = $_SESSION['user']->id;
				$mAuthenticationBean->user = $_SESSION['user']->id;
				$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
				$request = new Requestv2(
					"v2/AuthenticationRequestHandler",
					UPDATE,
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
				
			} else {
				$request = new Requestv2(
					"v2/AuthenticationRequestHandler",
					READ,
					array(
						"login"=>$_POST['email'],
						"password"=>hash('sha512', $_POST['password']), 
						"passwordCheck"=>1 //fast yes/no password check instead of delivering an accesstoken
						)
				);
				
				try {
					
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
					
					if($responseObject->status != 200) {
						throw new Exception(_("Wrong password"));
					}
					
				} catch (Exception $e){
					$this->error = $e->getMessage();
					$this->renderView("updateProfile");
				}
			}
			
			$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
			$_POST['login'] = $_POST["email"];
			unset($_POST['password']);// /\ don't store people password! or we could deal with justice
			
			$request = new Requestv2(
				"v2/ProfileRequestHandler",
				UPDATE,
				array("user"=>json_encode($_POST))
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