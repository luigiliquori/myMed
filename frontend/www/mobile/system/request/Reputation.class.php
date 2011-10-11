<?php 
require_once 'system/request/Request.class.php';
require_once 'system/handler/IRequestHandler.php';

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
		$response = parent::send();
// 		$check = json_decode($response);
// 		if($check->error != null) {
// 			return $check->error->message;
// 		} else {
			return $response;
// 		}
	}
}
?>
