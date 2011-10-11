<?php 
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';
require_once 'system/beans/MUserBean.class.php';
require_once 'system/beans/MAuthenticationBean.class.php';

/**
 * RequestHandler for the profile management
 * @author lvanni
 *
 */
class UpdateProfileHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function handleRequest() { 
		// TRY TO REGISTER A NEW ACCOUNT
		if(isset($_POST['update'])) {
			
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
			$check1 = json_decode($response);
			if(isset($check1->error)) {
				$this->error = $check1->error->message;
				return;
			}
			
			// update the authentication
			$mAuthenticationBean = new MAuthenticationBean();
			$mAuthenticationBean->login =  $mUserBean->login;
			$mAuthenticationBean->user = $mUserBean->id;
			$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
			$request = new Request("AuthenticationRequestHandler", UPDATE);
			$request->addArgument("id", $_SESSION['user']->login); // the id of the authentication is the current login
			$request->addArgument("authentication", json_encode($mAuthenticationBean));
			$response = $request->send();
			//Check if there's not error
			$check2 = json_decode($response);
			if(isset($check2->error)) {
				$this->error = $check2->error->message;
				return;
			}
			
			$_SESSION['user'] = $check1;
			header("Refresh:0;url=".$_SERVER['PHP_SELF']);
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