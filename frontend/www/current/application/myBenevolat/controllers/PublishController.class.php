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

include("models/EmailNotification.class.php");

/**
 * Valid methods for PublishController are:
 *  - create: render the NewPublicationView that permits user to fill and
 *  		  submit a new publiction
 *  - submit: called from NewPublicationView, submit a new publication
 *
 */
class PublishController extends ExtendedProfileRequired {
	
	/**
	 * HandleRequest
	 */
	public function handleRequest() { 

		parent::handleRequest();
		
		// Execute the called controller method 
		switch ($_GET['method']){
			// Show the user publications list 
			case 'show_user_announcements':
				$this->showUserAnnouncement();
				break;
					
			// Show the NewPublication view
	        case 'new_announcement':
	        	$this->renderView("NewAnnouncement");
	        	break;
	        
	        // Modify a user's publication
	        case 'modify_announcement':
	        	$this->modifyAnnouncement();
	        	break;  
	        case 'delete_all':
	        	$this->deleteAllPublications();
	        	break;
		} 
	}
	
	function deleteAllPublications(){
		$search = new Annonce();
		$search->publisher = $_SESSION['user']->id;
		$search->publisherID = $_SESSION['user']->id;
		$this->result = $search->delete();
		$this->showUserAnnouncement();
	}


	/**
	 *  Submit a new user announcement
	 */
	public function create($fromUpdate=false) {
	
		// Check if submit method has been called from the form
		if (!($_SERVER['REQUEST_METHOD'] == "POST")) {
			$this->renderView("NewAnnouncement");
				
		} else {
			if (empty($_POST['title'])) {
				$this->error = _("Title field can't be empty");
			} else if ((empty($_POST['expire_day']) || empty($_POST['expire_month']) || empty($_POST['expire_year'])) || empty($_POST['date'])) {		
				$this->error = _("Please provide a valide expiration date");
			} else if (empty($_POST['text'])) {
				$this->error = _("Text field can't be empty");
					
			} else if (empty($_POST['competences'])) {
				$this->error = _("You have to choose at least 1 skill");
					
			} else if(count($_POST['competences'])>4) {
				$this->error = _("You can choose 4 skills maximum");
				
			} else if (empty($_POST['mission'])) {
				$this->error = _("You have to choose one mission type");	
		    }
		    if(!empty($this->error) && $fromUpdate==false){
		    	$this->renderView("NewAnnouncement");
		    }else if(!empty($this->error) && $fromUpdate==true){
		    	$this->redirectTo("?action=publish&method=modify_announcement&id=".$_POST['id']);
		    }else{
		    	debug("UPDATE ".$fromUpdate);
				// All required fields are filled, publish it
				$obj = new Annonce();
				$obj->publisher = $_POST['publisher'];	// Publisher ID
				$obj->type = "annonce";
				if(isset($_POST['id'])) $obj->id = $_POST['id'];
				else $obj->id = uniqid();
				$obj->competences = $_POST['competences'];	// array of competences	
				$obj->typeMission = $_POST['mission'];	
				$obj->quartier = $_POST['quartier'];
				if(isset($_POST['begin'])) $obj->begin = $_POST['begin'];
				else $obj->begin = date(DATE_FORMAT);
				$obj->end 	= $_POST['date'];					
				
				$title = str_replace('"',"", $_POST['title']);
				$title = str_replace('\'',"", $title);
				$title = str_replace('’', "", $title);
				
				$obj->title = $title;					
				$obj->text = $_POST['text'];	
				if(isset($_POST['promue'])) $obj->promue = $_POST['promue'];
				else $obj->promue = "false";
				if(isset($_POST['validated'])) $obj->validated = $_POST['validated']; // update
				else{ // create
					if($_POST['permission']=="2") $obj->validated = "validated";// auto validation if admin
					else $obj->validated = "waiting"; 				
				}
				
				// sets the level of broadcasting in the Index Table
				$level = 3;  
				$obj->publish($level);
	
				$this->success = _("Your announcement offer has been successfully published");
				if($fromUpdate==false){
					$this->redirectTo("?action=publish&method=show_user_announcements");
				}
			}
		}
	}
	
	public function update() {
		// Modification on keywords pred doesn't overwrite the old object but duplicate
		$request = new Annonce();
		$request->id = $_POST['id'];
		$res = $request->find();
		$oldAnn = $res[0];
		$oldAnn->getDetails();
		$oldAnn->delete(); //delete the old announcement
		
		$this->create(true); //create the new one
		
		$this->success = _("Announcement modified !");
		$this->redirectTo("?action=publish&method=show_user_announcements");
	}
	
	
	/**
	 *  Delete user's publication and all the applies
	 */
	public function delete() {
		$this->delete_Applies();
		
		$request = new Annonce();
		$request->id = $_POST['id'];
		$res = $request->find();
		$obj = $res[0];
		$obj->getDetails();
		
		$obj->delete();
		$this->result = $obj;
		$this->success = "Deleted !";
		
		if(isset($_POST['msgMail'])){ // deleted by the admin -> send a mail to the author to inform him
			$msgMail = "";
			if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the admin: "').$_POST['msgMail'].'"';
			
			$mailman = new EmailNotification(substr($_POST['author'],6),_("Your announcement has been removed"),_("Your announcement ").$_POST['title']._(" has been removed by an admin.").$msgMail);
			$mailman->send();
		}
		
		$this->showUserAnnouncement();
	}
	
	function delete_Applies(){
		$search_by_userid = new Apply();
		$search_by_userid->pred1 = 'apply&'.$_POST['id'].'&'.$_POST['author'];
		$result = $search_by_userid->find();
		
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
	
	
	/**
	 *  Search user's announcement and render MyAnnouncementView
	 */
	private function showUserAnnouncement() {
		// Search User publications
		$search = new Annonce();
		$search->publisher = $_SESSION['user']->id;  
		$this->result = $search->find();
		
		// Get user reputation
		$this->getReputation($this->result);
		
		$this->renderView("MyAnnouncement");
	}
	
	
	/**
	 * 
	 */
	private function modifyAnnouncement() {
		$request = new Annonce();
		$request->id = $_GET['id'];
		$res = $request->find();
		$obj = $res[0];
		$obj->getDetails();
		
		// Give this to the view
		$this->result = $obj;

		// Render the view
		$this->renderView("ModifyAnnouncement");
	}
	
	
	/**
	 * Get the reputation of the user in each application
	 * @param unknown $applicationList
	 */
	private function getReputation($resultList) {
		foreach($resultList as $item) :
			
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  APPLICATION_NAME);
		$request->addArgument("producer",  $item->id.$item->publisherID);
		$request->addArgument("consumer",  $_SESSION['user']->id);
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
	
		// Save reputation values
		$this->reputationMap[$item->id.$item->publisherID] = $value;
		$this->noOfRatesMap[$item->id.$item->publisherID] = $responseObject->dataObject->reputation->noOfRatings;
	
		endforeach;
	
	}
}

?>