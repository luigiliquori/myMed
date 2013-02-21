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
		switch ($_GET['method'])
		{
			// Show the user publications list 
			case 'show_user_publications':
				$this->showUserPublications();
				break;
					
			// Show the NewPublication view
	        case 'new_publication':
	        	$this->renderView("NewPublication");
	        	break;
	        
	        // Modify a user's publication
	        case 'modify_publication':
	        	$this->modifyPublication();
	        	break;  
		} 

	}


	/**
	 *  Submit a new user publication
	 */
	public function create() {
	
		// Check if submit method has been called from the form
		if (!($_SERVER['REQUEST_METHOD'] == "POST")) {
				
			// If the method has not post parameters, just render the
			// NewPublication View
			$this->renderView("NewPublication");
				
		} else {
	
			// Check mandatory fields
			if (empty($_POST['title'])) {
				$this->error = _("Title field can't be empty");
				$this->renderView("NewPublication");
			} else if (((empty($_POST['expire_day']) || 
					     empty($_POST['expire_month']) || 
					     empty($_POST['expire_year']))) &&
					     empty($_POST['date'])) {		
				$this->error = _("Please provide a valide expiration date for the course");
				$this->renderView("NewPublication");
			} else if (empty($_POST['text'])) {
				$this->error = _("Text field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['locality'])) {
				$this->error = _("Locality field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['language'])) {
				$this->error = _("Language field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['category'])) {
				$this->error = _("Category field can't be empty");
				$this->renderView("NewPublication");
			} else {
				
				// All required fields are filled, publish it
				$obj = new myEuroCINPublication();
				$obj->publisher = $_SESSION['user']->id;    	// Publisher ID
				$obj->locality = $_POST['locality'];			// locality
				$obj->language = $_POST['language'];			// Language
				$obj->category = $_POST['category'];			// Category
				$obj->end 	= $_POST['date'];					// Expiration date
				$obj->title = $_POST['title'];					// Title
				$obj->text 	= $_POST['text'];					// Publication text
				
				// Save publication date
				if(isset($_POST['begin'])) 
					$obj->begin = $_POST['begin'];
				else 
					$obj->begin = date(DATE_FORMAT);
				
				// If the author is an admin the post is automatically
				// validated
				if($_SESSION['myEuroCIN']->permission == '2' || 
				   $obj->validated == "validated")
					$obj->validated = "validated";		
				else			
					$obj->validated = "waiting";
				
				// sets the level of broadcasting in the Index Table
				$level = 3;  
				$obj->publish($level);
	
				$this->success = _("Your publication offer has been successfully published");
	
				// Return to publish view
				$this->redirectTo("?action=publish&method=show_user_publications");
	
			}
		}
	
	}
	
	
	/**
	 *  Update (modify) user's publication
	 */
	public function update() {
	
		// Update is just re-publish on the same predicate
		$this->create();
	}
	
	
	/**
	 *  Delete user's publication and all the students applies if category=course and the comments
	 */
	public function delete() {
		
		$this->delete_Comments();
		
		$obj = new myEuroCINPublication();
		$obj->publisherID = $_SESSION['user']->id;  	// Publisher ID
		$obj->publisher = $_SESSION['user']->id;    	// Publisher ID
		//$obj->type = 'myEuroCINPublication';			// Publication type no used anymore
		$obj->locality = $_POST['locality'];			// Locality
		$obj->language = $_POST['language'];			// Organization
		$obj->category = $_POST['category'];			// Category
		$obj->end 	= $_POST['date'];					// Expiration date
		$obj->title = $_POST['title'];					// Title
		$obj->text 	= $_POST['text'];					// Publication text
		$obj->validated = $_POST['validated'];			// Publication text
		
		// Delete publication
		$obj->delete();
		$this->result = $obj;
		$this->success = "Deleted !";
		
		// Render MyPublications View
		$this->showUserPublications();
	}
	
	function delete_Applies(){
		$search_by_userid = new Apply();
		$search_by_userid->pred1 = 'apply&'.$_POST['predicate'].'&'.$_POST['author'];
		$result = $search_by_userid->find();
		
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
	
	function delete_Comments(){
		$search_by_userid = new Comment();
		$search_by_userid->pred1 = 'comment&'.$_POST['predicate'].'&'.$_POST['author'];
		$result = $search_by_userid->find();
		
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
	
	
	/**
	 *  Search user's publication and render MyPublicationView
	 */
	private function showUserPublications() {
		// Search User publications
		$search = new myEuroCINPublication();
		$search->publisher = $_SESSION['user']->id;  
		$this->result = $search->find();
		
		// Get user reputation
		$this->getReputation($this->result);
		
		$this->renderView("MyPublication");
	}
	
	
	/**
	 *  Search user's publication and render MyPublicationView
	 */
	private function modifyPublication() {
	
		// Get arguments of the query
		$predicate = $_GET['predicate'];
		$author = $_GET['author'];
	
		// Create an object
		$obj = new myEuroCINPublication($predicate);
		$obj->publisherID = $author;
	
		// Fetches the details
		$obj->getDetails();
	
		// Give this to the view
		$this->result = $obj;
	
		// get author reputation
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  APPLICATION_NAME);
		$request->addArgument("producer",  $obj->publisherID);
		$request->addArgument("consumer",  $obj->publisherID);
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
	
		// Save reputation values
		$this->reputation["author"] = $value;
		$this->reputation["author_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;
	
		// get value reputation
		$request->addArgument("producer",  $predicate.$obj->publisherID);
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
	
		// Save reputation values
		$this->reputation["value"] = $value;
		$this->reputation["value_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;
	
		// Render the view
		$this->renderView("ModifyPublication");
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