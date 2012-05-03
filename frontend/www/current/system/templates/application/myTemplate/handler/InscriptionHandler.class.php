<?php require_once 'lib/dasp/request/Request.class.php'; ?>
<?php require_once 'lib/dasp/beans/MUserBean.class.php'; ?>
<?php require_once 'lib/dasp/beans/MAuthenticationBean.class.php'; ?>
<?php require_once 'lib/dasp/request/IRequestHandler.php'; ?>

<?php 
class InscriptionHandler implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public /*String*/ function handleRequest() { 
		
		if(isset($_POST['inscription'])) {
			
			// Preconditions
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "FAIL: password != confirmation";
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
			
			// register the new account
			$request = new Request("AuthenticationRequestHandler", CREATE);
			$request->addArgument("authentication", json_encode($mAuthenticationBean));
			$request->addArgument("user", json_encode($mUserBean));
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);

			if($responseObject->status != 200) {
				$_SESSION['error'] = $responseObject->description;
			} else {
				$this->success = "Félicitation, Un email de confirmation vient de vous être envoyé!";
			}
		} else if (isset($_GET['inscription'])) {
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