<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MyOpportunityManagementController extends AuthenticatedController {
	
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		
		if(isset($_POST['addSubscription'])){
			$this->addSubscription();
		}
		
		if(isset($POST['removeSubscription'])){
			$this->removeSubscription();
		}
		
		$this->getSubscription();	
		$this->renderView("MyOpportunityManagement");
	}
	
	
	/**
	 * add a subscription
	 */
	function addSubscription(){
		$subscribeObject = new MyEduSubscriptionBean();
		$subscribeObject->pred1 = $_POST['Category'];
		$subscribeObject->pred2 = $_POST['organization'];
		$subscribeObject->pred3 = $_POST['locality'];
		$subscribeObject->pred4 = $_POST['Area'];
		$subscribeObject->subscribe();
		$this->success = _("Subscribe success");
	}
	
	/**
	 * remove subscription
	 */
	function removeSubscription(){
		
	}
	
	/**
	 * get all subscriptions
	 */
	function getSubscription(){
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();
		$this->response = $responsejSon;
	}
}

