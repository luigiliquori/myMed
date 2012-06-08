<?php 
require_once '../../../lib/dasp/request/Publish.class.php';
require_once '../../../lib/dasp/request/Find.class.php';
require_once '../../../lib/dasp/request/Delete.class.php';
require_once '../../../lib/dasp/request/GetDetail.class.php';
require_once '../../../lib/dasp/request/Subscribe.class.php';
require_once '../../../lib/dasp/request/StartInteraction.class.php';
require_once '../../../lib/dasp/request/Request.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyApplicationHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
		if(isset($_POST['method'])) {
			if($_POST['method'] == "publish") {
				$publish = new Publish($this);
				return $publish->send();
			} else if($_POST['method'] == "subscribe") {
				$subscribe = new Subscribe($this);
				return $subscribe->send();
			} else if($_POST['method'] == "find") {
				$find = new Find($this);
				return $find->send();
			} else if($_POST['method'] == "getDetail") {
				$getDetail = new GetDetail($this);
				return $getDetail->send();
			} else if($_POST['method'] == "delete") {
				$delete = new Delete($this);
				return $delete->send();
			} else if($_POST['method'] == "startInteraction") {
				$startInteraction = new StartInteraction($this);
				return $startInteraction->send();
			} 
		} 
	}
	
	/* --------------------------------------------------------- */
	/* Getter&Setter */
	/* --------------------------------------------------------- */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*void*/ function setError(/*String*/ $error){
		return $this->error = $error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	public /*void*/ function setSuccess(/*String*/ $success){
		return $this->success = $success;
	}
	
}
?>