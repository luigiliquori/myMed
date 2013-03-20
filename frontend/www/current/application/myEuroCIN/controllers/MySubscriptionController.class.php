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

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MySubscriptionController extends AuthenticatedController {
	
	public $result;
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		
		if (isset($_GET['subscriptions'])) {	
			// render all the publications
			$this->find_publication();
			
			$this->renderView("MySubscription");
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
		return $subscriptionsRaw;
	}
	
	/**
	 * find publication according to subscriptions
	 */
	function find_publication(){
		$subscriptionsRaw = $this->getSubscription();
		error_log("LOGROM subRAw ". count($subscriptionsRaw));
		if(count($subscriptionsRaw) !=0  ){
			$this->search_result = array();
			foreach ($subscriptionsRaw as $key=>$values){
				$preds = explode("pred",$key);
				$search = new myEuroCINPublication();
				foreach($preds as $key2){
					switch($key2{0}){
						case '1':
							$search->category= substr($key2,1);
							break;
						case '2':
							$search->organization = substr($key2,1);
							break;
						case '4':
							$search->area = substr($key2,1);
							break;
					}
				}
				$this->result= $search->find();
				error_log("LOGROM RES: ".$this->result[1]);
				array_push($this->search_result,$search->find());
			}
		}
	}

}
?>