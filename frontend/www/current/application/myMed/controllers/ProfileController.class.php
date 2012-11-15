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
				$this->renderView("profile");
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
					$res = $request->send();
				} catch (Exception $e){
					$this->error = _("Wrong password");
					$this->renderView("profile");
				}
			}
			

			// update the authentication
	// 		$mAuthenticationBean = new MAuthenticationBean();
	// 		$mAuthenticationBean->login =  $_SESSION['user']->email;
	// 		$mAuthenticationBean->user = $_SESSION['user']->id;
	// 		$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
			
	// 		$request = new Request("AuthenticationRequestHandler", UPDATE);
	// 		$request->addArgument("authentication", json_encode($mAuthenticationBean));
			
	// 		$request->addArgument("oldLogin", $_SESSION['user']->email);
	// 		$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));
			
	// 		$responsejSon = $request->send();
	// 		$responseObject1 = json_decode($responsejSon);
			
	// 		if($responseObject1->status != 200) {
	// 			$this->error = $responseObject1->description;
	// 		}
			
			// update the profile
			
			
			/*$mUserBean = new MUserBean();
			$mUserBean->id = $_SESSION['user']->id;
			$mUserBean->firstName = $_POST["firstName"];
			$mUserBean->lastName = $_POST["lastName"];
			$mUserBean->name = $_POST["firstName"] . " " . $_POST["lastName"];
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
			unset($_POST['password']);// /\ don't store people password! or we could deal with justice
			
			$request = new Requestv2(
				"v2/ProfileRequestHandler",
				UPDATE,
				array("user"=>json_encode($_POST))
			);
	
			$responsejSon = $request->send();
			$responseObject2 = json_decode($responsejSon);
	
			if($responseObject2->status != 200) {
				$this->error = $responseObject2->description;
			} else{
	
				$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $_POST);
			}
		}

	}

}
?>