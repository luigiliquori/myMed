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
		
		$this->subscribetype= "subscriptionInfos";
		
		if(isset($_POST['addSubscription'])){
			$this->addSubscription();
		}
		
		if(isset($_POST['removeSubscription'])){
			$this->removeSubscription();
		}
		
		$this->getSubscription();	
		$this->renderView("mySubscriptionManagement");
	}
	
	
	/**
	 * add a subscription
	 */
	function addSubscription(){
		//retrieve variables
		if(empty($_POST['competence']) && empty($_POST['mobility']) && empty($_POST['mission'])){
			$this->error = _("You have to choose at least one parameter");
			$this->renderView("mySubscriptionManagement");
		}
		$competence = $_POST['competence'];
		$mobility = $_POST['mobility'];
		$mission =  $_POST['mission'];
		if(!isset($_POST['nameSub']) || $_POST['nameSub'] == ""){
			$pubTitle = "S".time();
		}
		else{
			$pubTitle = $_POST['nameSub'];
		}
		//$this->sub = $cat." ".$organization." ".$locality." ".$area." ".$pubTitle;
		
		//create subscription
		$subscribeObject = new Annonce();
		$subscribeObject->competences = $competence;
		$subscribeObject->quartier = $mobility;
		$subscribeObject->typeMission = $mission;
		$subscribeObject->subscribe();
		
		//publish a subscription object
		$publishSubObject = new MyBenevolatSubscriptionBean();
		$publishSubObject->type = $this->subscribetype;
		$publishSubObject->pubTitle = $pubTitle;
		$publishSubObject->competence = $competence;
		$publishSubObject->mobility = $mobility;
		$publishSubObject->mission = $mission;
		$publishSubObject->publisher = $_SESSION['user']->id;
		$publishSubObject->publisherID = $_SESSION['user']->id;
		$publishSubObject->publish(3);
		$this->success = _("Subscribe success");
	}
	
	/**
	 * remove subscription
	 */
	function removeSubscription(){
		//remove subscription
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $_SESSION['user']->id);
		$request->addArgument("predicate",$_POST['predicate']);
		$request->send();
		
		//remove subscription object
		error_log($_POST['publicationTitle']);
		//$object = json_decode($_POST['publicationTitle']);
		error_log(var_dump($object));
		$deleteObject = new MyBenevolatSubscriptionBean();
		$deleteObject->type = $this->subscribetype;
		$deleteObject->publisherID = $_SESSION['user']->id;
		$deleteObject->publisher = $_SESSION['user']->id;
		//error_log("LOGROM : pubTitle ". $object->pubTitle." type: ".$object->type);
		$deleteObject->pubTitle = $_POST['publicationTitle'];
		$deleteObject->delete();
		$this->success = _("Subscription Deleted");
	}
	
	/**
	 * get all subscriptions
	 */
	function getSubscription(){
		$findSub = new MyBenevolatSubscriptionBean();
		$findSub->type =$this->subscribetype ;
		$findSub->publisher = $_SESSION['user']->id;
		$this->response = $findSub->find();
	}
	
}

