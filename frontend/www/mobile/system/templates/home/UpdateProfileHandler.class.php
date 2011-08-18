<?php 
class UpdateProfileHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public /*String*/ function handleRequest() { 
		// TRY TO REGISTER A NEW ACCOUNT
		if(isset($_POST['update'])) {
			
			// Preconditions
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "FAIL: inscription != confirmation";
				return;
			} else if($_POST['password'] == ""){
				$this->error = "FAIL: password cannot be empty!";
				return;
			} else if($_POST['email'] == ""){
				$this->error = "FAIL: email cannot be empty!";
				return;
			} else if(!$_POST['checkCondition']){
				$this->error = "FAIL: you may accept the condition";
				return;
			}
			
			// update the profile
			$mUserBean = new MUserBean();
			$mUserBean->id = $_SESSION['user']->id;
			$mUserBean->firstName = $_POST["prenom"];
			$mUserBean->lastName = $_POST["nom"];
			$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
			$mUserBean->email = $_POST["email"];
			$mUserBean->login = $_POST["email"];
			$mUserBean->birthday = $_POST["birthday"];
			$mUserBean->profilePicture = $_POST["thumbnail"];
			$request = new Request("ProfileRequestHandler", UPDATE);
			$request->addArgument("user", json_encode($mUserBean));
			$response = $request->send();
			// Check if there's not error
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
				return;
			}
			
			// update the authentication
// 			$mAuthenticationBean = new MAuthenticationBean();
// 			$mAuthenticationBean->login =  $mUserBean->login;
// 			$mAuthenticationBean->user = $mUserBean->id;
// 			$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
// 			$request = new Request("AuthenticationRequestHandler", UPDATE);
// 			$request->addArgument("authentication", json_encode($mAuthenticationBean));
// 			$response = $request->send();
			// Check if there's not error
// 			$check = json_decode($response);
// 			if($check->error != null) {
// 				$this->error = $check->error->message;
// 				return;
// 			}
			
			$this->success = "INFO: The profile has been successfully updated!";
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