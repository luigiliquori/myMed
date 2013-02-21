<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MySubscriptionController extends AuthenticatedController {
	
	public $result;
	public $search_result;
	public $subscriptions_name;
	public $actual_subscription_name;
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		$this->subscribetype= "subscriptionInfos";
		if (isset($_GET['subscriptions'])){
			//render last subscription publications
			error_log("LOGROM: sub list ".$_POST["Subscription_list"]);
			if(isset($_POST["Subscription_list"])){
				$this->find_define_publication($_POST["Subscription_list"]);
			}
			else{
				$this->find_default_publication();
			}		
		}
		$this->renderView("mySubscription");
	}
	
	
	/**
	 * get subscriptions
	 */
	function getSubscription($pubTitle){
		$findSub = new MyBenevolatSubscriptionBean();
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
		$sub_array=$this->getSubscription();
		if(count($sub_array) != 0){
			$this->actual_subscription_name = $sub_array[0]->pubTitle;
			$this->find_publication($sub_array[0]->category, $sub_array[0]->locality,$sub_array[0]->organization,$sub_array->area);
		}
	}
	
	function find_define_publication($pubTitle){
		//get subscription name
		$sub=$this->getSubscription();
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
		$find_pub = new Annonce();
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