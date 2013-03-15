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
	public function create($fromUpdate=false) {
	
		// Check if submit method has been called from the form
		if (!($_SERVER['REQUEST_METHOD'] == "POST")) {
				
			// If the method has not post parameters, just render the
			// NewPublication View
			$this->renderView("NewPublication");
				
		} else {
			// Check mandatory fields
			if (empty($_POST['data'])) {
				$this->error = _("Title field can't be empty");
			//} else if($fromUpdate==false && (empty($_POST['expire_day']) || empty($_POST['expire_month']) || empty($_POST['expire_year']) || empty($_POST['date']))) {		
			//	$this->error = _("Please provide a valide expiration date");
			} else if (empty($_POST['text'])) {
				$this->error = _("Text field can't be empty");	
			} else if (empty($_POST['Nazione'])) {
				$this->error = _("Locality field can't be empty");
			} else if (empty($_POST['Lingua'])) {
				$this->error = _("Language field can't be empty");	
			}
			
			if(!empty($this->error) && $fromUpdate==false){
		    	$this->renderView("NewPublication");
		    }else if(!empty($this->error) && $fromUpdate==true){
		    	debug($this->error);
		    	$this->redirectTo("?action=publish&method=modify_publication&predicate=".$_POST['predicate']."&author=".$_POST['author']);
		    } else {
				
				// All required fields are filled, publish it
				$obj = new myEuroCINPublication();
				$obj->type = "myEuroCIN";    					// Type
				$obj->publisher = $_SESSION['user']->id;    	// Publisher ID
				$obj->Lingua = $_POST['Lingua'];				// locality
				$obj->Nazione = $_POST['Nazione'];				// Language
				if($_POST['expire_date'] != "--")
					$obj->expire_date = $_POST['expire_date'];	// Expiration date
				$obj->data = $_POST['data'];					// Title
				$obj->text 	= $_POST['text'];					// Publication text
				if( isset($_POST['Arte_Cultura']) ) $obj->Arte_Cultura = "on";
				if( isset($_POST['Natura']) ) $obj->Natura = "on";
				if( isset($_POST['Tradizioni']) ) $obj->Tradizioni = "on";
				if( isset($_POST['Enogastronomia']) ) $obj->Enogastronomia = "on";
				if( isset($_POST['Benessere']) ) $obj->Benessere = "on";
				if( isset($_POST['Storia']) ) $obj->Storia = "on";
				if( isset($_POST['Religione']) ) $obj->Religione = "on";
				if( isset($_POST['Escursioni_Sport']) ) $obj->Escursioni_Sport = "on";
				
				// Save publication date
				if(isset($_POST['begin'])) 
					$obj->begin = $_POST['begin'];
				else 
					$obj->begin = date(DATE_FORMAT);
				
			    if(isset($_POST['validated'])) $obj->validated = $_POST['validated']; // update
				else{ // create
					if($_SESSION['myEuroCIN']->permission=='2') $obj->validated = "validated"; // auto validation if admin
					else $obj->validated = "waiting"; 				
				}
				
				// sets the level of broadcasting in the Index Table
				$level = 2;  
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
		$this->create(true);
	}
	
	
	/**
	 *  Delete user's publication and all the comments
	 */
	public function delete() {
		
		$this->delete_Comments();
		
		$obj = new myEuroCINPublication();
		$obj->publisherID = $_POST['publisher'];  	// Publisher ID
		$obj->type = "myEuroCIN";    					// Type
		$obj->publisher = $_POST['publisher'];    	// Publisher ID
		$obj->Lingua = $_POST['Lingua'];				// locality
		$obj->Nazione = $_POST['Nazione'];				// Language
		$obj->begin = $_POST['begin'];
		$obj->expire_date 	= $_POST['expire_date'];	// Expiration date
		$obj->data = $_POST['data'];					// Title
		$obj->validated = $_POST['validated'];			// Publication text
		$obj->text 	= $_POST['text'];					// Publication text
		if( isset($_POST['Arte_Cultura']) ) $obj->Arte_Cultura = "on";
		if( isset($_POST['Natura']) ) $obj->Natura = "on";
		if( isset($_POST['Tradizioni']) ) $obj->Tradizioni = "on";
		if( isset($_POST['Enogastronomia']) ) $obj->Enogastronomia = "on";
		if( isset($_POST['Benessere']) ) $obj->Benessere = "on";
		if( isset($_POST['Storia']) ) $obj->Storia = "on";
		if( isset($_POST['Religione']) ) $obj->Religione = "on";
		if( isset($_POST['Escursioni_Sport']) ) $obj->Escursioni_Sport = "on";
		
		$obj->validated = $_POST['validated'];			// Publication text
		
		// Delete publication
		$obj->delete();
		$this->result = $obj;
		$this->success = "Publication deleted !";
		
		if(isset($_POST['msgMail'])){ // deleted by the admin -> send a mail to the author to inform him
			$msgMail = "";
			if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the admin: "').$_POST['msgMail'].'"';
				
			$mailman = new EmailNotification(substr($_POST['publisher'],6),_("Your publication has been removed"),_("Your publication ").$_POST['title']._(" has been removed by an admin.").$msgMail);
			$mailman->send();
		}
		
		$this->renderView("Main");
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
		$search->type = "myEuroCIN";
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