<?php 
require_once 'system/handler/IRequestHandler.php';
require_once 'system/beans/MDataBean.class.php';
require_once 'system/request/Publish.class.php';
require_once 'system/request/Subscribe.class.php';
require_once 'system/request/Find.class.php';
require_once 'system/request/GetDetail.class.php';
require_once 'system/request/StartInteraction.class.php';

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
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() { 
		if(isset($_POST['method'])) {
			if($_POST['method'] == "publish") {
				$publish = new Publish($this);
				$publish->send();
			} else if($_POST['method'] == "subscribe") {
				$subscribe = new Subscribe($this);
				$subscribe->send();
			} else if($_POST['method'] == "find") {
				$find = new Find($this);
				$find->send();
			} else if($_POST['method'] == "getDetail") {
				$getDetail = new GetDetail($this);
				$getDetail->send();
			} else if($_POST['method'] == "startInteraction") {
				$startInteraction = new StartInteraction($this);
				$startInteraction->send();
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