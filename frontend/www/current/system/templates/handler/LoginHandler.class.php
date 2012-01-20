<?php require_once 'lib/dasp/request/Request.class.php'; ?>
<?php require_once 'lib/dasp/beans/MUserBean.class.php'; ?>
<?php require_once 'lib/dasp/beans/MAuthenticationBean.class.php'; ?>

<?php require_once 'lib/socialNetworkAPIs/SocialNetworkConnection.class.php'; ?>

<?php require_once 'system/templates/handler/IRequestHandler.php'; ?>

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
		
		// Only if there is no existing session
		if(!isset($_SESSION['user'])) {
			
			// LOGIN
			if(isset($_GET['accessToken'])) {
				
				if(isset($_GET['registration'])) { // HANDLER REGISTRATION
					$request = new Request("AuthenticationRequestHandler", CREATE);
					$request->addArgument("accessToken", $_GET['accessToken']);
					
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
					
					if($responseObject->status != 200) {
						$_SESSION['error'] = $responseObject->description;
					} else {
						header("Refresh:0;url=" . $url . "?inscription=ok"); // REDIRECTION
					}
				} else { // HANDLE LOGIN
					$request = new Request("SessionRequestHandler", READ);
					$request->addArgument("accessToken", $_GET['accessToken']);
					if(isset($_GET['socialNetwork'])){
						$request->addArgument("socialNetwork", $_GET['socialNetwork']);
					} else {
						$request->addArgument("socialNetwork", "myMed");
					}
				
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
				
					if($responseObject->status != 200) {
						$_SESSION['error'] = $responseObject->description;
					} else {
						$_SESSION['user'] = json_decode($responseObject->data->user);
						if(!isset($_SESSION['friends'])){
							$_SESSION['friends'] = array();
						}
						$_SESSION['accessToken'] = $_GET['accessToken'];
					}
				}
				
			} else if(isset($_POST['singin'])) { // HANDLE MYMED AUTHENTICATION
				
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
					$url = $responseObject->data->url;
					header("Refresh:0;url=" . $url . "?accessToken=" . $accessToken); // REDIRECTION
				}
				
			} else { // HANDLE ALL THE SOCIAL NETWORK AUTHENTICATION
				
				foreach($this->socialNetworkConnection->getWrappers() as $wrapper) {
					$wrapper->handleAuthentication();
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