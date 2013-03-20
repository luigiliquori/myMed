<!--
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
 -->
<?php

class FindController extends AuthenticatedController{
	
	public /*String*/ function handleRequest() {
		
		parent::handleRequest();
	
		if ($_SERVER['REQUEST_METHOD'] == "POST") { // search button			
			$search = new MyEduPublication();
			$this->fillObj($search); // for the filters

			$res = $search->find(); 
			$this->filter_array($res);
			
			$this->getReputation($this->result);
			$this->renderView("results");
		}
	}
	
	function defaultMethod() {
		if(isset($_GET['delete_publications'])){
			$search = new MyEduPublication();

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
			$search = new MyEduPublication();
			$res = $search->find(); 
			$this->filter_array($res);
			
			// get userReputation
			$this->getReputation($this->result);
			$this->renderView("Find");
		}
	}
	
	private function filter_array($res1){
		// filter by the expiration date
		$date = strtotime(date('d-m-Y')); 
	
		$this->result = array();
		foreach($res1 as $item):
			if(!empty($item->end) && $item->end!="--"){
				$expiration_date = strtotime($item->end);
				if($date <= $expiration_date){
					array_push($this->result, $item);
				}
			}else{ // no expiration date
				array_push($this->result, $item);
			}
		endforeach;
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		$obj->publisher = $_POST['publisher'];
		
		if(isset($_POST['localityBox']) && $_POST['localityBox'] && isset($_POST['locality'])) $obj->locality = $_POST['locality'];
		if(isset($_POST['areaBox']) && $_POST['areaBox'] && isset($_POST['area'])) $obj->area = $_POST['area'];
		if(isset($_POST['organizationBox']) && $_POST['organizationBox'] && isset($_POST['organization'])) $obj->organization = $_POST['organization'];
		
		if(isset($_POST['category'])) $obj->category = $_POST['category'];
		$obj->type = 'myEduPublication';
		//$obj->end 	= $_POST['date'];

		//$obj->title = $_POST['title'];
		//$obj->text 	= $_POST['text'];
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
		$search = new MyEduPublication();
		$search->publisher = $_SESSION['user']->id;
		$search->publisherID = $_SESSION['user']->id;
		$this->result = $search->delete();
	}
}

?>