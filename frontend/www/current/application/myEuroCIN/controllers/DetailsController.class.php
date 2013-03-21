<? 
class DetailsController extends AuthenticatedController {
	
	public $reputation = array();
	
	public function handleRequest() {
		
		parent::handleRequest();
		/* Guest access provided */
		if (!(isset($_SESSION['user'])) && $_SESSION['user']->is_guest) {
			$id = rand(100000, 999999);
			$user = (object) array('id'=>'MYMED_'.$id, 'name'=>'user'.$id);
			$_SESSION['user'] = insertUser($user, null, true);
			$_SESSION['acl'] = array('defaultMethod', 'read');
			$_SESSION['user']->is_guest = 1;
		}
		
		// Get arguments of the query
		$predicate = $_GET['predicate'];
		$author = $_GET['author'];
		
		//signing up datas to the session will be used during adding comments
		$_SESSION['predicate'] = $predicate;
		$_SESSION['author'] = $author;
		
		// Create an object
		$obj = new myEuroCINPublication($predicate);
		$obj->publisherID = $author;
		
		// Fetches the details
		$obj->getDetails();
		 
		// Give this to the view
		$this->result = $obj;
		
		// BACKWARD COMPATIBILITY: If validated is not setted, It is a 
		// Publication from the old version of myEuroCIN, set validated to true
		if(!isset($this->result->validated)) $this->result->validated="validated"; 
			
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
		
		// Save the reputation and the number of voters
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
		
		// Save the reputation and the number of ratings
		$this->reputation["value"] = $value;
		$this->reputation["value_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;
		
		$this->search_comment();
		
		try {
			$datamapper = new DataMapper;
			$details = $datamapper->findById(new User($author));
			$this->publisher_profile = new myEuroCINProfile($details['profile']);
			$this->publisher_profile->details = $datamapper->findById($this->publisher_profile);
		} catch (Exception $e) {
			
			// BACKWARD COMPATIBILITY: It is an old publication, user that 
			// published it has not got an ExtendedProfile
			$this->result->old_publication = "true";	
			//$this->redirectTo("main");
		}
		
		// BACKWARD COMPATIBILITY: It is an old publication, user cannot modify it
		if(!isset($this->result->type))
			$this->result->old_publication = "true";
				
		// Render the view
		$this->renderView("details");
	}
	
	function defaultMethod() {
		
		if (isset($_GET['comment'])){
			$this->comment();
		}
	}
	
	//searching comments
	public function search_comment() {
		$search_comments = new Comment();
		$this->fillObjComment($search_comments);
		$this->result_comment = $search_comments->find();
	
	}

	private function fillObjComment($obj) {
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
	}
}
?>