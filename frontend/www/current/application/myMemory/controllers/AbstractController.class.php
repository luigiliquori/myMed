<?

abstract class AbstractController implements IRequestHandler {

	public /*string*/ $error;
	public /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	/** Generate a HTTP redirect to the given action and exit */
	public function redirectTo($action) {
		header("Refresh:0;url=/application/".APPLICATION_NAME."/index.php?action=$action");
		exit();
	}
	
	/** Renders a view by including it, and exit */
	public function renderView($view) {			
		$view = ucfirst($view);
		$viewPath = APP_ROOT . "/views/${view}View.php";
		include($viewPath);
		exit();
	}
	
	
}

?>