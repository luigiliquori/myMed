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
class Publish extends Request {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("PublishRequestHandler", CREATE);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		parent::addArgument("application", $_POST['application']);
		// construct the predicate + data
		$predicate = "";
		$data = array();
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->ontologyID < 4) { // it's a predicate
				$predicate .= $ontology->value;
			}
			$data[$i] = $ontology;
		}
		// construct the request
		parent::addArgument("predicate", $predicate);
		parent::addArgument("data", json_encode($data));
		parent::addArgument("user", json_encode($_SESSION['user']));
		
		$response = parent::send();
		$check = json_decode($response);
		if($check->error != null) {
			$this->handler->setError($check->error->message);
		} else {
			$this->handler->setSuccess("Request sent!");
		}
	}
}
?>