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
	public function redirectTo($action, array $vars=null) {
		if(isset($vars)){
			$get_line = "";
			foreach($vars as $key=>$value){
				$get_line .= '&' . $key . '=' . $value;
			}
			printf('<script>location.href="/application/'.APPLICATION_NAME.'/index.php?action='.$action.$get_line.'"</script>');
			//header('Refresh:0;url=/application/'.APPLICATION_NAME.'/index.php?action=' . $action . $get_line);
		}
		else{
			debug("redirectiiiiing!");
			printf('<script>location.href="/application/'.APPLICATION_NAME.'/index.php?action='.$action.'"</script>');
			//header('Refresh:0;url=/application/'.APPLICATION_NAME.'/index.php?action='.$action);
		}
			
		exit();
	}
	
	/** Renders a view by including it, and exit */
	public function renderView($view) {			
		$view = ucfirst($view);
		$viewPath = APP_ROOT . "/views/${view}View.php";
		require($viewPath);
		exit();
	}
	
	/**
	 * Call another controller 
	 */
	public function forward($action, $method=null, $args =	array()) {
		
		// Override $_GET method with arguments
		foreach($args as $key => $val) {
			$_GET[$key] = $val;
			$_POST[$key] = $val;
		}
		
		// Call another controller
		callController($action, $method);
	}
	
	public /*void*/ function setError($message){
		$this->error = $message;
	}
	
	public /*void*/ function setSuccess($message){
		$this->success = $message;
	}
	
	
	/** Default method called after each request*/
	public function defaultMethod() {
		// Should be overridden for controller that use the "method" parameter 
	}
	
	
	
}

?>