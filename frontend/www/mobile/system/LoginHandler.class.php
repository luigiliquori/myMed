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
		// HANDLE ALL THE SOCIAL NETWORK AUTHENTICATION
		foreach($this->socialNetworkConnection->getWrappers() as $wrapper) {
			$wrapper->handleAuthentication();
		}
		
		// HANDLE AUTHENTICATION
		if(isset($_POST['singin'])) { 
			// Preconditions
			if($_POST['login'] == ""){
				$this->error = "FAIL: eMail cannot be empty!";
				return;
			} else if($_POST['password'] == ""){
				$this->error = "FAIL: password cannot be empty!";
				return;
			}
			
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_POST["login"]);
			$request->addArgument("password", hash('sha512', $_POST["password"]));
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$_SESSION['error'] = $responseObject->description;
			} else {
				$accessToken = $responseObject->data->accessToken;
				header("Refresh:0;url=" . $_SERVER['PHP_SELF'] . "?socialNetwork=myMed&accessToken=" . $accessToken); // REDIRECTION
			}
		}
		
		// HANDLE LOGIN
		if(isset($_GET['accessToken'])) {
			$request = new Request("SessionRequestHandler", READ);
			$request->addArgument("accessToken", $_GET['accessToken']);
			$request->addArgument("socialNetwork", $_GET['socialNetwork']);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$_SESSION['error'] = $responseObject->description;
			} else {
				$_SESSION['user'] = json_decode($responseObject->data->profile);
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