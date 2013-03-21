<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MySubscriptionManagementController extends AuthenticatedController {
	
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		
		if(isset($_POST['addSubscription'])){
			$this->addSubscription();
		}
		
		if(isset($_GET['removeSubscription'])){
			$this->removeSubscription();
		}
		
		$this->getSubscription();	
		$this->renderView("MySubscriptionManagement");
	}
	
	
	/**
	 * add a subscription
	 */
	function addSubscription(){
		$subscribeObject = new myEuroCINSubscriptionBean();
		$subscribeObject->pred1 = $_POST['Category'];
		$subscribeObject->pred2 = $_POST['organization'];
		$subscribeObject->pred4 = $_POST['Area'];
		$subscribeObject->subscribe();
		$this->success = _("Subscribe success");
	}
	
	/**
	 * remove subscription
	 */
	function removeSubscription(){
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $_SESSION['user']->id);
		$request->addArgument("predicate",$_GET['predicate']);
		$request->send();
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

