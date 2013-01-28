<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MyOpportunityController extends AuthenticatedController {
	
	public $result;
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		if (isset($_GET['opportunities'])){
			debug("OPPORTUNITIES CALL");
			// render all the publications
			//$selectedResults = new MyEduPublication();
			//$this->result = $selectedResults->find();
			// get userReputation
			//$this->getReputation($this->result);
			$this->getSubscription();
			
			
			$this->renderView("MyOpportunity");
		}
	}
	
	
	
	/**
	 * get all subscriptions
	 */
	function getSubscription(){
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();
		$response = json_decode($responsejSon);
		$subscriptionsRaw = (array)$response->dataObject->subscriptions;
		$this->subscriptions = array();
		foreach ($subscriptionsRaw as $key=>$values){
			$this->subscriptions->push();
		}
	}
}

?>