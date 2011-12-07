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
class StartInteraction extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("InteractionRequestHandler", UPDATE);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		parent::addArgument("application", $_POST['application']);
		parent::addArgument("producer", $_POST['producer']);
		parent::addArgument("consumer", $_POST['consumer']);
		parent::addArgument("start", $_POST['start']);
		parent::addArgument("end", $_POST['end']);
		parent::addArgument("predicate", $_POST['predicate']);
		if(isset($_POST['snooze'])){
			parent::addArgument("snooze", $_POST['snooze']);
		}
		// in case of "simple interaction"
		if(isset($_POST['feedback'])){
			parent::addArgument("feedback", $_POST['feedback']);
		}
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
		} else {
			$this->handler->setSuccess($responseObject->description);
		}
	}
}
?>