<?

abstract class AbstractController implements IRequestHandler {

	public /*string*/ $error;
	public /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public function redirectTo($action) {
		header("Refresh:0;url=/application/".APPLICATION_NAME."/index.php?action=$action");
		exit();
	}
}

?>