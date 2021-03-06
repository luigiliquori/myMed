<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<? 

include 'Mobile_Detect.php';

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends ExtendedProfileRequired {

	public $detect; // Mobile detect
	public $result;
	public $reputationMap = array();

	public function MainController() {
		parent::__construct();
		$this->detect = new Mobile_Detect();
	}

	public function handleRequest() {
		
		parent::handleRequest();
		
		if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			// -- Search
			$search = new Partnership();
			$this->fillObj($search);
			$this->result = $search->find();
				
			// get userReputation
			$this->getReputation($this->result);
				
			$this->renderView("results");
							
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {

			$obj = new Partnership();
			
			// Fill the object
			$_POST['publisher'] = $_SESSION['user']->id;
			$this->fillObj($obj);
			$obj->publisherID = $_SESSION['user']->id;
			$obj->delete();
			$this->result = $obj;
			$this->success = "Deleted !";
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Subscribe") {
				
			// -- Subscribe
			$obj = new Partnership();

			// Fill the object
			$this->fillObj($obj);
			$obj->subscribe();

			$this->success = "Subscribe !";
				
		} else {
			
			// load "Our Selection results"
			$selectedResults = new Partnership();
			$this->result = $selectedResults->find();
			// get userReputation
			$this->getReputation($this->result);
		}

		$this->renderView("main");
	}

	/**
	 * Fill object with POST values
	 * @param unknown $obj
	 */
	private function fillObj($obj) {
		
		$obj->publisher = $_POST['publisher'];
		
		$obj->theme = $_POST['theme'];
		$obj->other = $_POST['other'];

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