<?

abstract class AbstractController implements IRequestHandler {

	public /*string*/ $error;
	public /*string*/ $success;
	public /*string*/ $name; // without 'Controller'
	
	public function __construct($name) {
		$this->error	= false;
		$this->success	= false;
		$this->name = $name;
	}
	
	/** 
	 * Generate a HTTP redirect to the given action and exit
	 * Optional argument for passing GET vars
	 * Optional arg for passing url hash '#aa'
	 */
	public function redirectTo($action, $vars=array(), $hash="") {
		
		$url = url($action, $vars);
		$url .= $hash;

		debug($url);
		?>
		<html>
			<head>
				<!-- HTML reload (for data-ajax=false) -->
				<script>
					location.href='<?= $url ?>'
				</script>
			</head>
			<body>
				<!-- Change page : JQueryMobile redirect -->
				<div data-role="page">
					<script>
						$.mobile.changePage('<?= $url ?>');
					</script>
				</div>
			</body>
		</html>
		<?
		exit();
	}
	
	/** Renders a view by including its file, and exit */
	public function renderView($view) {	
	
		$view = ucfirst($view);

 		if ($view == "Login" || $view == "Register"){
 			$viewPath = MYMED_ROOT . "/application/myMed/views/${view}View.php";
 		} else {
			$viewPath = APP_ROOT . "/views/${view}View.php";
 		}
		
		// Set ERROR and SUCCESS
		global $ERROR; 
		$ERROR = $this->error;
		global $SUCCESS; 
		$SUCCESS = $this->success;
		
		include($viewPath);
		exit();
	}
	
	/**
	 * Call another controller.
	 * forward the status and error.
	 * @param $action "action:method"
	 */
	public function forwardTo($action, $args = array()) {
		
		// Override $_GET method with arguments
		foreach($args as $key => $val) {
			$_GET[$key] = $val;
			$_POST[$key] = $val;
			$_REQUEST[$key] = $val;
		}
		
		$parts = explode(":", $action);
		$action = $parts[0];
		
		$method = null;
		if (sizeof($parts) > 1) $method = $parts[1];
		
		// Call another controller
		callController($action, $method, $this->success, $this->error);
	}
	
	public /*void*/ function setError($message){
		$this->error = $message;
	}
	
	public /*void*/ function setSuccess($message){
		$this->success = $message;
	}
	
	
	// Handlers

	/** No action by default */
	public function handleRequest(){
		
	}
	
	/** Default method called after each request*/
	/*public function defaultMethod() {
		// Should be overridden for controller that use the "method" parameter 
	}*/
	
	/** Default fallback method called after an access denied, an error...*/
	public function error($arguments) {
		debug('>>>> error !');
		debug_r($arguments);
		// Should be overridden for controller that use the "method" parameter
	}

	
		
	public function getReputation($app=array(APPLICATION_NAME), $producer=array()){
	
		$rep =  new ReputationSimple($app, $producer);
		$res = $rep->send();
		if($res->status != 200) {
			throw new Exception($res->description);
		} else {
			return formatReputation($res->dataObject->reputation);
		}
	}
	
	public function insertUser($user, $accessToken, $is_guest=false) {
	
		$request = new Requestv2("v2/SessionRequestHandler", UPDATE , array("user"=>$user->id, "accessToken"=>$accessToken));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
			return;
		} else {
			$_SESSION['accessToken'] = $responseObject->dataObject->accessToken; // in case was not set yet
		}
	
		$request = new Requestv2("v2/ProfileRequestHandler", UPDATE , array("user"=>json_encode($user)));
		if ($is_guest) $request->addArgument("temporary", "");
		$responsejSon = $request->send();
		//$responseObject2 = json_decode($responsejSon);
	
		//fetch profile, since it can be completed from  a previous different auth way with same login
		$request = new Requestv2("v2/ProfileRequestHandler", READ , array("userID"=>$user->id));
		$responsejSon = $request->send();
		$responseObject3 = json_decode($responsejSon);
		
		return (object) array_map('trim', (array) $responseObject3->dataObject->user);
		/*if($responseObject3->status == 200) {
			$prevEmail = isset($_SESSION['user']->email);
			$_SESSION['user2'] = $_SESSION['user']; //keep it just for seeing the diff (debug)
			$_SESSION['user'] = (object) array_map('trim', (array) $responseObject3->dataObject->user);
	
		}*/
	}
	
	
}

?>