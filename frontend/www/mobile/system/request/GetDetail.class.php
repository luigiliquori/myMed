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
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->error != null) {
			$this->handler->setError($responseObject->description);
		} else {
			$this->handler->setSuccess($responseObject->data->details);
		}
	}
}
?>