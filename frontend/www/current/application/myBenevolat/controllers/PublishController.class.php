<?php 

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
	public function create() {
	
		// Check if submit method has been called from the form
		if (!($_SERVER['REQUEST_METHOD'] == "POST")) {
			$this->renderView("NewAnnouncement");
				
		} else {
			if (empty($_POST['title'])) {
				$this->error = _("Title field can't be empty");
				$this->renderView("NewAnnouncement");
			} else if ((empty($_POST['expire_day']) || empty($_POST['expire_month']) || empty($_POST['expire_year'])) && empty($_POST['date'])) {		
				$this->error = _("Please provide a valide expiration date for the course");
				$this->renderView("NewAnnouncement");
			} else if (empty($_POST['text'])) {
				$this->error = _("Text field can't be empty");
				$this->renderView("NewAnnouncement");
					
			} else if (empty($_POST['competences'])) {
				$this->error = _("You have to choose at least 1 skill");
				$this->renderView("NewAnnouncement");
					
			} else if(count($_POST['competences'])>4) {
				$this->error = _("You can choose 4 skills maximum");
				$this->renderView("NewAnnouncement");
				
			} else if (empty($_POST['mission'])) {
				$this->error = _("You have to choose one mission type");
				$this->renderView("NewAnnouncement");
					
		    } else {
				
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
				$obj->title = $_POST['title'];					
				$obj->text = $_POST['text'];	
				if(isset($_POST['promue'])) $obj->promue = $_POST['promue'];
				else $obj->promue = "false";
				if(isset($_POST['validated'])) $obj->validated = $_POST['validated'];
				else $obj->validated = "waiting";				
				
				
				// sets the level of broadcasting in the Index Table
				$level = 3;  
				$obj->publish($level);
	
				$this->success = _("Your announcement offer has been successfully published");
	
				// Return to publish view
				$this->redirectTo("?action=publish&method=show_user_announcements");
	
			}
		}
	
	}
	
	public function update() {
		// Modification on keywords pred doesn't overwrite the old object but duplicate
		//$predicate = $_POST['predicate'];
		//$author = $_POST['author'];
		
		//$oldAnn = new Annonce($predicate);
		//$oldAnn->publisherID = $author;
		//$oldAnn->getDetails();
		$request = new Annonce();
		$request->id = $_POST['id'];
		$res = $request->find();
		$oldAnn = $res[0];
		$oldAnn->getDetails();
		
		$oldAnn->delete(); //delete the old announcement
		
		$this->create(); //create the new one
		
		if(!empty($this->error)){
			$this->redirectTo("?action=publish&method=modify_announcement&predicate=".$_POST['predicate']."&author=".$_POST['author']);
		}
		else{
			$this->success = _("Announcement modified !");
			$this->showUserAnnouncement();
		}
	}
	
	
	/**
	 *  Delete user's publication and all the students applies if category=course and the comments
	 */
	public function delete() {
		$this->delete_Applies();
		
		//$predicate = $_POST['predicate'];
		//$author = $_POST['author'];
		
		// Create an object
		//$obj = new Annonce($predicate);
		//$obj->publisherID = $author;
		
		// Fetches the details
		//$obj->getDetails();
		
		$request = new Annonce();
		$request->id = $_POST['id'];
		$res = $request->find();
		$obj = $res[0];
		$obj->getDetails();
		
		$obj->delete();
		$this->result = $obj;
		$this->success = "Deleted !";
		
		$this->showUserAnnouncement();
	}
	
	function delete_Applies(){
		$search_by_userid = new Apply();
		$search_by_userid->pred1 = 'apply&'.$_POST['predicate'].'&'.$_POST['author'];
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
		//$predicate = $_GET['predicate'];
		//$author = $_GET['author'];
		
		// Create an object
		//$obj = new Annonce($predicate);
		//$obj->publisherID = $author;
		
		// Fetches the details
		//$obj->getDetails();
		
		$request = new Annonce();
		$request->id = $_POST['id'];
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