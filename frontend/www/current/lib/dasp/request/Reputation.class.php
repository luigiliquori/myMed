<?php 
require_once 'lib/dasp/request/Request.class.php';
require_once 'system/templates/handler/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Reputation extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		parent::__construct("ReputationRequestHandler", READ);
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function getReputation(/*String*/ $application, /*String*/ $producer, /*String*/ $consumer){
		parent::addArgument("application", $application);
		parent::addArgument("producer", $producer);
		parent::addArgument("consumer", $consumer);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status == 200) {
			return $responseObject->data->reputation;
		} else {
			return $responseObject->description;
		}
	}
}
?>
