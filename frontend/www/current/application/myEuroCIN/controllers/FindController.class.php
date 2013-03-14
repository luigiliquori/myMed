<?php

class FindController extends AuthenticatedController{
	
	public /*String*/ function handleRequest() {
		
		parent::handleRequest();
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // search button			
							
			$search = new myEuroCINPublication();

			if($_POST['locality']) $search->Nazione = $_POST['locality'];
			if($_POST['language']) $search->Lingua = $_POST['language'];
			if($_POST['Arte_Cultura']) $search->Arte_Cultura = "on";
			if($_POST['Natura']) $search->Natura = "on";
			if($_POST['Tradizioni']) $search->Tradizioni = "on";
			if($_POST['Enogastronomia']) $search->Enogastronomia = "on";
			if($_POST['Benessere']) $search->Benessere = "on";
			if($_POST['Storia']) $search->Storia = "on";
			if($_POST['Religione']) $search->Religione = "on";
			if($_POST['Escursioni_Sport']) $search->Escursioni_Sport = "on";
			
			// Filter out the publication that has not been validated yet
			$results = $search->find();
			$this->filter_array($results);
			
			// Get reputations 	
			$this->getReputation($this->result);
			$this->renderView("results");
			
		} else if (isset($_GET['search'])) {
			
			// Find posts in all languages
			$results = array();
			$search = new myEuroCINPublication();
			$search->Lingua = "italiano";
			$results = array_merge($results, $search->find());
			$search->Lingua = "francese";
			$results = array_merge($results, $search->find());
			$search->Lingua = "inglese";
			$results = array_merge($results, $search->find());
			
			//if($_SESSION['user']->lang == 'it' ) $search->Lingua = "italiano";
			//if($_SESSION['user']->lang == 'fr' ) $search->Lingua = "francese";
			//if($_SESSION['user']->lang == 'en' ) $search->Lingua = "inglese";
			
			// Filter out the publication that has not been validated yet
			$this->filter_array($results);

			// get userReputation
			$this->getReputation($this->result);
			$this->renderView("Find");
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
			 
			$this->filter_array($this->result);
				
			// get userReputation
			$this->getReputation($this->result);
			$this->renderView("Find");
		}
		
	}
	
	/* Fill object with POST values */
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
	
	
	/* Filter results basing on validate fields */
	private function filter_array($res1){
		
		// filter by the expiration date
		$date = strtotime(date(DATE_FORMAT));
		$res2 = array();
		foreach($res1 as $item):
			// Check expiration data if is defined
			if(!empty($item->expire_date) && $item->expire_date!="--") {
				$expiration_date = strtotime($item->expire_date);
				if($date <= $expiration_date){
					array_push($res2, $item);
				}
			} else {
			// Otherwise add to results
				array_push($res2, $item);
			}
		endforeach;
	
		// filter by the validation status
		$this->result = array();
		foreach($res2 as $item):
			$item->getDetails();
			if(!isset($item->validated) || $item->validated=="validated"){
				array_push($this->result, $item);
			}
		endforeach;
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