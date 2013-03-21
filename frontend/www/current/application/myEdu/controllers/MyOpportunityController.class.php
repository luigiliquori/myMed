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
<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MyOpportunityController extends AuthenticatedController {
	
	public $result;
	public $search_result;
	public $subscriptions_name;
	public $actual_subscription_name;
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		$this->subscribetype= "subscriptionInfos";
		if (isset($_GET['opportunities'])){
			//render last subscription publications
			
			if(isset($_POST["Subscription_list"])){
				error_log("LOGROM: sub list ".$_POST["Subscription_list"]);
				$this->find_define_publication($_POST["Subscription_list"]);
			}
			else{
				$this->find_default_publication();
			}		
		}
		$this->renderView("myOpportunity");
	}
	
	
	/**
	 * get subscriptions
	 */
	function getSubscription($pubTitle){
		$findSub = new MyEduSubscriptionBean();
		$findSub->type =$this->subscribetype ;
		$findSub->publisher = $_SESSION['user']->id;
		if(!empty($pubTitle)){
			$findSub->pubTitle = $pubTitle;
		}
		$sub_array = $findSub->find();
		$this->get_publication_list($sub_array);
		return $sub_array;
	}
	
	function find_default_publication(){
		$sub_array=$this->getSubscription(null);
		if(count($sub_array) != 0){
			$this->actual_subscription_name = $sub_array[0]->pubTitle;
			$this->find_publication($sub_array[0]->category, $sub_array[0]->locality,$sub_array[0]->organization,$sub_array[0]->area);
		}
	}
	
	function find_define_publication($pubTitle){
		//get subscription name
		$sub=$this->getSubscription(null);
		$this->actual_subscription_name=$pubTitle;
		foreach($sub as $val){
			if($val->pubTitle == $pubTitle){
				$subscription= $val;
			}
		}
		$this->find_publication($subscription->category, $subscription->locality, $subscription->organization, $subscription->area);
	}
	
	function get_publication_list($sub_array){
		$this->subscriptions_name = array();
		foreach($sub_array as $value){
			array_push($this->subscriptions_name, $value->pubTitle);
		}
	}
	
	/**
	 * find publication according to subscriptions
	 */
	function find_publication($category,$locality,$organization,$area){
		$find_pub = new MyEduPublication();
		$find_pub->category=$category;
		$find_pub->area = $area;
		$find_pub->locality = $locality;
		$find_pub->organization=$organization;
		$this->search_result = $find_pub->find();
		$this->getReputation($this->search_result);
	}
	
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