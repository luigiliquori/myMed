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
class MySubscriptionManagementController extends AuthenticatedController {
	
	static $subscribetype= "subscriptionInfos";
	
	public /*String*/ function handleRequest() {
		parent::handleRequest();
	}
	
	function defaultMethod() {
		
		if(isset($_POST['addSubscription'])){
			$this->addSubscription();
		}
		
		if(isset($_POST['removeSubscription'])){
			$this->removeSubscription();
		}
		
		$this->getSubscription();	
		$this->renderView("MySubscriptionManagement");
	}
	
	
	/**
	 * add a subscription
	 */
	function addSubscription() {
		
		//retrieve variables
		if(empty($_POST['Category']) && empty($_POST['organization']) && empty($_POST['Area'])) {
			
			$this->error = _("You have to choose at least one parameter");
			$this->renderView("myOpportunityManagement");
		}
		
		$cat = $_POST['Category'];
		$organization = $_POST['organization'];
		$area = $_POST['Area'];
		
		if(!isset($_POST['nameSub']) || $_POST['nameSub'] == ""){
			$pubTitle = "S".time();
		}
		else{
			$pubTitle = $_POST['nameSub'];
		}
		$this->sub = $cat." ".$organization." ".$area." ".$pubTitle;
		
		//create subscription
		$subscribeObject = new myTemplateExtendedPublication();
		$subscribeObject->category = $cat;
		$subscribeObject->organization = $organization;
		$subscribeObject->area = $area;
		$subscribeObject->subscribe();
		
		//publish a subscription object
		$publishSubObject = new MyTemplateExtendedSubscriptionBean();
		$publishSubObject->type = MySubscriptionManagementController::$subscribetype;
		$publishSubObject->pubTitle = $pubTitle;
		$publishSubObject->category = $cat;
		$publishSubObject->organization = $organization;
		$publishSubObject->area = $area;
		$publishSubObject->publisher = $_SESSION['user']->id;
		$publishSubObject->publisherID = $_SESSION['user']->id;
		$publishSubObject->publish(3);
		$this->success = _("Subscribe success");
	}
	
	/**
	 * remove subscription
	 */
	/*function removeSubscription(){
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
		$deleteObject = new MyEduSubscriptionBean();
		$deleteObject->type = $this->subscribetype;
		$deleteObject->publisherID = $_SESSION['user']->id;
		$deleteObject->publisher = $_SESSION['user']->id;
		//error_log("LOGROM : pubTitle ". $object->pubTitle." type: ".$object->type);
		$deleteObject->pubTitle = $_POST['publicationTitle'];
		$deleteObject->delete();
		$this->success = _("Subscription Deleted");
	}*/
	
	function removeSubscription(){
		MySubscriptionManagementController::removeSubscriptionStatic($_SESSION['user']->id,$_POST['predicate'],$_POST['publicationTitle']);
		$this->success = _("Subscription Deleted");
	}
	
	/**
	 * remove subscription
	 */
	static function removeSubscriptionStatic($user,$predicate="none",$publicationTitle="none"){
		//remove subscription
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("userID", $user);
		if(predicate != "none"){
			$request->addArgument("predicate",$predicate);
		}
		$request->send();
	
		//remove subscription object
		$deleteObject = new MyTemplateExtendedSubscriptionBean();
		$deleteObject->type = MySubscriptionManagementController::$subscribetype;
		$deleteObject->publisherID = $user;
		$deleteObject->publisher = $user;
		//error_log("LOGROM : pubTitle ". $object->pubTitle." type: ".$object->type);
		if($publicationTitle != "none"){
			$deleteObject->pubTitle = $publicationTitle;
		}
		$deleteObject->delete();
	}
	
	/**
	 * get all subscriptions
	 */
	function getSubscription(){
		$findSub = new MyTemplateExtendedSubscriptionBean();
		$findSub->type =MySubscriptionManagementController::$subscribetype;
		$findSub->publisher = $_SESSION['user']->id;
		$this->response = $findSub->find();
	}
	
}


