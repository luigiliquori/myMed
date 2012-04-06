<?php 
require_once 'lib/dasp/request/IRequestHandler.php';
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'lib/dasp/request/Publish.class.php';
require_once 'lib/dasp/request/Subscribe.class.php';
require_once 'lib/dasp/request/Find.class.php';
require_once 'lib/dasp/request/GetDetail.class.php';
require_once 'lib/dasp/request/StartInteraction.class.php';

require_once 'system/templates/application/' . APPLICATION_NAME . '/lib/Convert.class.php';
 


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