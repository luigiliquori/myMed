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

		if(fopen(APP_ROOT . "/views/${view}View.php", "r")) {
			$viewPath = APP_ROOT . "/views/${view}View.php";
		} else {
			$viewPath = MYMED_ROOT . "/application/myMed/views/${view}View.php";
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

	
}

?>