<?php require_once 'system/request/Request.class.php'; ?>
<?php require_once 'system/handler/IRequestHandler.php'; ?>
<?php require_once 'system/beans/MUserBean.class.php'; ?>
<?php require_once 'system/beans/MAuthenticationBean.class.php'; ?>
<?php require_once 'socialNetworkAPIs/SocialNetworkConnection.class.php'; ?>

<?php 
/**
 * 
 * Enter description here ...
 * @author lvanni
 *
 */
class LoginHandler implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	private /*SocialNetworkConnection*/ $socialNetworkConnection;
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->socialNetworkConnection = new SocialNetworkConnection();
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public /*String*/ function handleRequest() { 
		// HANDLE ALL THE SOCIAL NETWORK LOGIN
		foreach($this->socialNetworkConnection->getWrappers() as $wrapper) {
			$wrapper->handleLogin();
		}
		
		// HANDLE MYMED LOGIN
		if(isset($_POST['singin'])) { 
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
			if(isset($_POST['isMobileConnection'])) {
				$request->addArgument("password", $_POST["password"]);	// the password should be already encrypted by the client
			} else {
				$request->addArgument("password", hash('sha512', $_POST["password"]));
			}
			$response = $request->send();
			// Check if there's not error
			$check = json_decode($response);
			if(isset($check->error)) {
				$_SESSION['error'] = $check->error->message;
			} else if($check->firstName != null) {
				$user = $check;
				// AUTHENTENTICATION OK: CREATE A SESSION
				$request = new Request("SessionRequestHandler", CREATE);
				$request->addArgument("userID", $user->id);
				$response = $request->send();
				$check = json_decode($response);
				if(isset($check->error)) {
					$_SESSION['error'] = $check->error->message;
				} else {
					$_SESSION['user'] = $user;
				}
			}
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public /*ISocialNetwork[]*/ function getSocialNetworks(){
		return $this->socialNetworks;
	}
}
?>