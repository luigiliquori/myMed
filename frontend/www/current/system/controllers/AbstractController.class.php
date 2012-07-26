<?

abstract class AbstractController implements IRequestHandler {

	public /*string*/ $error;
	public /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	/** 
	 * Generate a HTTP redirect to the given action and exit
	 * Optionnal argument for passing GET vars
	 */
	public function redirectTo($action, array $vars=array()) {
		$url = url($action, $vars);
		debug("url:" . $url);
		
		printf('<script>location.href="/application/'. APPLICATION_NAME . '/index.php' . $url . '"</script>');
		//header('Refresh:0;url=/application/'.APPLICATION_NAME.'/index.php?action=' . $action . $get_line);
			
		exit();
	}
	
	/** Renders a view by including its file, and exit */
	public function renderView($view) {			
		$view = ucfirst($view);
		$viewPath = APP_ROOT . "/views/${view}View.php";
		require($viewPath);
		exit();
	}
	
	/**
	 * Call another controller.
	 * forward the status and error.
	 * @param $action "action:method"
	 */
	public function forwardTo($action, $args = array()) {
		
		debug_r($args);
		
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
	public function handleRequest() {
		
	}
	
	/** Default method called after each request*/
	public function defaultMethod() {
		// Should be overridden for controller that use the "method" parameter 
	}
	
	
	
}

?>