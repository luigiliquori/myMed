<?php

class FindController extends AuthenticatedController{
	
	public /*String*/ function handleRequest() {
		
		parent::handleRequest();
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // search button			
			$search = new myEuroCINPublication();
			$this->fillObj($search); // for the filters

			$this->result = $search->find(); 
			$date = strtotime(date('d-m-Y')); 
				
			for($i = 0; $i < count($this->result); ++$i) {
				if(!empty($this->result[$i]->end) && $this->result[$i]->end!="--"){
					$expiration_date = strtotime($this->result[$i]->end);
					if($expiration_date < $date){
						unset($this->result[$i]);
					}
				}
			}
			$this->renderView("results");
		}
	}
	
	function defaultMethod() {
		if(isset($_GET['delete_publications'])){
			$search = new myEuroCINPublication();

			$search->publisher = $_SESSION['user']->id;
			$search->publisherID = $_SESSION['user']->id;
			
			$result = $search->find();
			foreach($result as $item) :
				$item->publisher = $_SESSION['user']->id;
				$item->publisherID = $_SESSION['user']->id;
				$item->delete();
			endforeach;
			
			$this->renderView("Find");
		}
		else if (isset($_GET['search'])){
			$search = new myEuroCINPublication();
			$this->result = $search->find(); 
			$date = strtotime(date('d-m-Y')); 
				
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
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		$obj->publisher = $_POST['publisher'];
		
		if($_POST['languageBox']) $obj->language = $_POST['language'];
		if($_POST['categoryBox']) $obj->category = $_POST['category'];
		
		$obj->locality = $_POST['locality'];
		$obj->type = 'myEuroCINPublication';
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
	
	function deleteAllPublications(){
		$search = new myEuroCINPublication();
		$search->publisher = $_SESSION['user']->id;
		$search->publisherID = $_SESSION['user']->id;
		$this->result = $search->delete();
	}
}

?>