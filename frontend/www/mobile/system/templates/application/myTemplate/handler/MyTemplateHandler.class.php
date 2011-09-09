<?php 
require_once 'system/request/Request.class.php';
require_once 'system/request/IRequestHandler.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyTemplateHandler implements IRequestHandler {
	
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
		if(isset($_GET['publish'])) {
			$request = new Request("PubSubRequestHandler", CREATE);
			$request->addArgument("application", "myTemplate");
			$request->addArgument("predicate", $_GET['start'].$_GET['end'].$_GET['date']);
			$request->addArgument("user", json_encode($_SESSION['user']));
			// construct the data
			$type = new Data("Value1", $_GET['type'], ENUM);
			$comment = new Data("Value2", urlencode($_GET['comment']), TEXT);
			$data = array($comment, $type);
			$request->addArgument("data", json_encode($data));
			
			$response = $request->send();
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
			} else {
				$this->success = "Request sent!";
			}
		} else if(isset($_GET['subscribe'])) {
			$request = new Request("PubSubRequestHandler", READ);
			$request->addArgument("application", "myTemplate");
			$request->addArgument("predicate", $_GET['start'].$_GET['end'].$_GET['date']);
			$response = $request->send();
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
		} else if(isset($_GET['getDetails'])) {
			$request = new Request("PubSubRequestHandler", READ);
			$request->addArgument("application", "myTemplate");
			$request->addArgument("predicate", $_GET['predicate']);
			$request->addArgument("user", $_GET['user']);
			$response = $request->send();
			$check = json_decode($response);
			if($check->error != null) {
				$this->error = $check->error->message;
			} else {
				$this->success = $response;
			}
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