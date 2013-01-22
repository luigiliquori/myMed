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
			case 'show':
				$this->showUserPublications();
				break;
					
			// Show the NewPublication view
	        case 'create':
	        	$this->renderView("NewPublication");
	        	break;
	        
	        // Submit a new user publication
	        case 'submit':   
	        	$this->submitNewPublication();
	        	break;
	        
	        // Modify a user's publication
	        case 'modify':
	        	$this->renderView("ModifyPublication");
	        	break;
  
		} 

	}
  
	
	function show() {
		$this->showUserPublications();
	}
	
	/**
	 *  Search user's publication and render MyPublicationView
	 */
	private function showUserPublications() {

		// Search User publications
		$search = new MyEduPublication();
		
		// TODO : remove fake user id used for testing
		// $_POST['publisher'] = $_SESSION['user']->id;
		$_POST['publisher'] = "FAKE_USER_ID";
		$obj->publisher = $_POST['publisher'];		// Publisher ID
		
		$this->result = $search->find();
		
		// Get user reputation
		$this->getReputation($this->result);
		
		$this->renderView("MyPublication");
		
	}
	
	
	/**
	 *  Submit a new user publication 
	 */
	private function submitNewPublication() {
		
		// Check if submit method has been called from the form
		if (!($_SERVER['REQUEST_METHOD'] == "POST")) {
			
			// If the method has not post parameters, just render the
			// NewPublication View
			$this->renderView("NewPublication");
			
		} else {
				
			// Check publication fields
			if (empty($_POST['title'])) {
				$this->error = _("Title field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['text'])) {
				$this->error = _("Text field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['area'])) {
				$this->error = _("Area field can't be empty");
				$this->renderView("NewPublication");
					
			} else if (empty($_POST['category'])) {
				$this->error = _("Category field can't be empty");
				$this->renderView("NewPublication");
					
			} else{
				
				// All required fields are filled, publish it
				$obj = new MyEduPublication();
		
				// Fill the Publication object
				// TODO : remove fake user id used for testing
				// $_POST['publisher'] = $_SESSION['user']->id;
				$_POST['publisher'] = "FAKE_USER_ID";
				
				$obj->publisher = $_POST['publisher'];		// Publisher ID
				$obj->type = 'myEduPublication';			// Publication type
				$obj->area = $_POST['area'];				// Area
				$obj->category = $_POST['category'];		// Category
				$obj->locality = $_POST['locality'];		// Category
				$obj->end 	= $_POST['date'];				// Expiration date
				$obj->title = $_POST['title'];				// Title
				$obj->text 	= $_POST['text'];				// Publication text
				$obj->publish();
		
				$this->success = _("Your publication offer has been successfully published");
		
				// Return to main controller
				$this->forwardTo("main");
		
			}
		}
	
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