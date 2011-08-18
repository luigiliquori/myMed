<?php 
class MenuHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public /*String*/ function handleRequest() { 
		if(isset($_GET['disconnect'])) {
			session_destroy();
			header("Refresh:0;url=".$_SERVER['PHP_SELF']);
			return;
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