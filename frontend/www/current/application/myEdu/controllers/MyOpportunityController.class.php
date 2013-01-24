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
			$this->renderView("MyOpportunity");
		}
	}
	
}

?>