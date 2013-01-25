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
		$obj = new MyEduPublication($predicate);
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
		
		// Need publisher role (student, professer, or company) so
		// get publisher details
		// Get the user details
		
		try {
			$datamapper = new DataMapper;
			$details = $datamapper->findById(new User($author));
			$this->publisher_profile = new MyEduProfile($details['profile']);
			$this->publisher_profile->details = $datamapper->findById($this->publisher_profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
				
		// Render the view
		$this->renderView("details");
	}
	
	function defaultMethod() {
		
		if (isset($_GET['comment'])){
			$this->comment();
		}
	}
	
	function comment(){
		debug("button comment");
		if(!empty($_POST['wrapped1']) ){
			$obj = new Comment();
					
			// Fill the object
			$this->fillObj_comments($obj);
			$obj->publish();
		
			$this->result = $obj;
		}
		debug($_SESSION['predicate']." ".$_SESSION['author']);
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	//searching comments
	public function search_comment() {
	
		// -- Search
		$search_comments = new Comment();
		$this->fillObj($search_comments);
		$this->result_comment = $search_comments->find();
	
	}
	
	private function fillObj_comments($obj) {
		$time = time();
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
		$obj->pred2 = $time;
		$obj->wrapped1 =$_POST['wrapped1'];
		$obj->wrapped2 =$_SESSION['user']->profilePicture;
	
	}
	
	private function fillObj($obj) {
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
	}
}
?>