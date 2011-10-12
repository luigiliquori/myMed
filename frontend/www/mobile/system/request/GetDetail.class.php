<?php 
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';
require_once 'system/beans/MDataBean.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class GetDetail extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("FindRequestHandler", READ);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		parent::addArgument("application", $_POST['application']);
		parent::addArgument("predicate", $_POST['predicate']);
		parent::addArgument("user", $_POST['user']);
		$response = parent::send();
		$check = json_decode($response);
		if($check->error != null) {
			$this->handler->setError($check->error->message);
		} else {
			$this->handler->setSuccess($response);
		}
	}
}
?>