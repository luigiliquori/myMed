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
class Subscribe extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("SubscribeRequestHandler", CREATE);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		parent::addArgument("application", $_POST['application']);
		// construct the predicate + data
		$predicate = "";
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->value != "") {
				$predicate .= $ontology->key . "(" . $ontology->value . ")";
			}
		}
		// construct the request
		parent::addArgument("predicate", urlencode($predicate));
		parent::addArgument("user", json_encode($_SESSION['user']));
	
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->error != null) {
			$this->handler->setError($responseObject->description);
		} else {
			$this->handler->setSuccess("Request sent!");
		}
	}
}
?>