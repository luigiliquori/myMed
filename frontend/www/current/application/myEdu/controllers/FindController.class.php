<?php

class FindController extends AuthenticatedController{
	
	public $result;
	
	public /*String*/ function handleRequest() {
		
		parent::handleRequest();
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // search button			
			$search = new MyEduPublication();
			$this->fillObj($search);
			$this->result = $search->find();
			$this->renderView("results");
		}
	}
	
	function defaultMethod() {
		if (isset($_GET['search'])){
			debug("SEARCH CALL");
			// render all the publications
			$selectedResults = new MyEduPublication();
			$this->result = $selectedResults->find();
			// get userReputation
			$this->getReputation($this->result);
			$this->renderView("Find");
		}
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		$obj->publisher = $_POST['publisher'];
		
		if($_POST['localityBox']) $obj->locality = $_POST['locality'];
		if($_POST['areaBox']) $obj->area = $_POST['area'];
		if($_POST['organizationBox']) $obj->organization = $_POST['organization'];
		
		$obj->category = $_POST['category'];

		$obj->end 	= $_POST['date'];

		$obj->title = $_POST['title'];
		$obj->text 	= $_POST['text'];
	}
	
	/**
	 * Get the reputation of the user in each application
	 * @param unknown $applicationList
	 */
	private function getReputation($resultList) {
		foreach($resultList as $item) :
			
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  APPLICATION_NAME);
		$request->addArgument("producer",  $item->getPredicateStr().$item->publisherID);
		$request->addArgument("consumer",  $_SESSION['user']->id);
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
	
		// Save reputation values
		$this->reputationMap[$item->getPredicateStr().$item->publisherID] = $value;
		$this->noOfRatesMap[$item->getPredicateStr().$item->publisherID] = $responseObject->dataObject->reputation->noOfRatings;
	
		endforeach;
	
	}
}

?>