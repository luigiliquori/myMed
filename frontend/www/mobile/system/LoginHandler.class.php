<?php require_once 'system/request/Request.class.php'; ?>
<?php require_once 'system/request/IRequestHandler.php'; ?>
<?php require_once 'system/beans/MUserBean.class.php'; ?>
<?php require_once 'system/beans/MAuthenticationBean.class.php'; ?>

<?php 
class LoginHandler implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public /*String*/ function handleRequest() { 
		// TRY TO REGISTER A NEW ACCOUNT
		if(isset($_POST['singin'])) { // TRY TO LOGIN
			// Preconditions
			if($_POST['login'] == ""){
				$this->error = "FAIL: eMail cannot be empty!";
				return;
			} else if($_POST['password'] == ""){
				$this->error = "FAIL: password cannot be empty!";
				return;
			}
			
			// AUTHENTICATION
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_POST["login"]);
			$request->addArgument("password", hash('sha512', $_POST["password"]));
			$response = $request->send();
			// Check if there's not error
			$check = json_decode($response);
			if(isset($check->error)) {
				$_SESSION['error'] = $check->error->message;
				return;
			} else if($check->firstName != null) {
				$user = $check;
				// AUTHENTENTICATION OK: CREATE A SESSION
				$request = new Request("SessionRequestHandler", CREATE);
				$request->addArgument("userID", $user->id);
				$response = $request->send();
				$check = json_decode($response);
				if(isset($check->error)) {
					$_SESSION['error'] = $check->error->message;
					return;
				} else {
					$_SESSION['user'] = $user;
					return;
				}
			}
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