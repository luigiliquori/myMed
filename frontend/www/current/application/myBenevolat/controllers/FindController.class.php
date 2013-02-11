<?php

class FindController extends AuthenticatedController{
	
	public /*String*/ function handleRequest() {
		
		parent::handleRequest();
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // search button			
			$search = new Annonce();
			
			if($_POST['competence']) $search->competences = $_POST['competence'];
			if($_POST['mission']) $search->typeMission = $_POST['mission'];	
			if($_POST['quartier']) $search->quartier = $_POST['quartier'];
			
			$search->validated='false';
			
			$this->result = $search->find(); 
			$date = strtotime(date(DATE_FORMAT)); 
				
			for($i = 0; $i < count($this->result); ++$i) {
				if(!empty($this->result[$i]->end) && $this->result[$i]->end!="--"){
					$expiration_date = strtotime($this->result[$i]->end);
					if($expiration_date < $date){
						unset($this->result[$i]);
					}
				}
			}
			$this->getReputation($this->result);
			$this->renderView("results");
		}
	}
	
	function defaultMethod() {
		if(isset($_GET['delete_publications'])){
			$search = new myBenevolatPublication();

			//$search->publisher = $_SESSION['user']->id;
			$search->publisherID = $_SESSION['user']->id;
			
			$result = $search->find();
			foreach($result as $item) :
				$item->publisher = $_SESSION['user']->id;
				$item->publisherID = $_SESSION['user']->id;
				$item->delete();
			endforeach;
			
			$this->renderView("Find");
		}
		else if (isset($_GET['search'])){ // from main menu: search annonce
			debug("FINDVIEW initialisation");
			
			$search = new Annonce();
			$search->validated='false';

			$this->result = $search->find();

			$date = strtotime(date(DATE_FORMAT));
				
			for($i = 0; $i < count($this->result); ++$i) {
				if(!empty($this->result[$i]->end) && $this->result[$i]->end!="--"){
					$expiration_date = strtotime($this->result[$i]->end);
					if($expiration_date < $date){
						unset($this->result[$i]);
					}
				}
			}
			// get userReputation
			$this->getReputation($this->result);
			$this->renderView("Find");
		}
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