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

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {
	
	public $result;
	public $reputationMap = array();

	public function handleRequest() {
		
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
			
			// -- Publish
			$obj = new ExampleObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->success = "Published !";
			
		} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
			// -- Search
			$search = new ExampleObject();
			$this->fillObj($search);
			$this->result = $search->find();
			
			// get userReputation
			foreach($this->result as $item) :
			
				// Get the reputation of the user in each application
				$request = new Request("ReputationRequestHandler", READ);
				$request->addArgument("application",  APPLICATION_NAME);
				$request->addArgument("producer",  $item->publisherID);		
				$request->addArgument("consumer",  $_SESSION['user']->id);
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if (isset($responseObject->data->reputation)) {
					$value =  json_decode($responseObject->data->reputation) * 100;
				} else {
					$value = 100;
				}
				$this->reputationMap[$item->publisherID] = $value;
			
			endforeach;
			
			$this->renderView("results");
			
		} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {

			$obj = new ExampleObject();				
			// Fill the object
			$this->fillObj($obj);
			$obj->publisherID = $_SESSION['user']->id;
			$obj->delete();			
			$this->result = $obj;	
			$this->success = "Deleted !";	
		} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Subscribe") {
			
			// -- Subscribe
			$obj = new ExampleObject();
				
			// Fill the object
			$this->fillObj($obj);
			$obj->subscribe();
				
			$this->success = "Subscribe !";
		}

		$this->renderView("main");
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		$obj->begin = $_POST['begin'];
		$obj->end = $_POST['end'];
		$obj->wrapped1 = $_POST['wrapped1'];
		$obj->wrapped2 = $_POST['wrapped2'];
		
		$obj->pred1 = $_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
		$obj->data2 = $_POST['data2'];
		$obj->data3 = $_POST['data3'];
	}
 	
}
?>