<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MyOpportunityController extends AuthenticatedController {
	
	public $result;
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		if (isset($_GET['opportunities'])){
			debug("OPPORTUNITIES CALL");
			// render all the publications
			//$selectedResults = new MyEduPublication();
			//$this->result = $selectedResults->find();
			// get userReputation
			//$this->getReputation($this->result);
			//$this->getSubscription();
			$this->find_publication();
			
			$this->renderView("MyOpportunity");
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
			//$this->search_result = array();
			foreach ($subscriptionsRaw as $key=>$values){
				$preds = explode("pred",$key);
				foreach($preds as $key2){
					$search = new MyEduPublication();
					switch($key2{0}){
						case '1':
							$search->category= substr($key2,1);
							break;
						case '2':
							$search->organization = substr($key2,1);
							break;
						case '3':
							$search->locality = substr($key2,1);
							break;
						case '4':
							$search->area = substr($key2,1);
							break;
					}
					$this->result= $search->find();
					error_log("LOGROM RES: ".$this->result[1]);

					//array_push($this->$search_result,$search->find());
				}
			}
		}
	}

}
?>