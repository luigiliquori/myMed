<? 
class BlogDetailsController extends AuthenticatedController {
	
	public $reputation = array();
	
	public function handleRequest() {
		
		parent::handleRequest();
		
			// Get arguments of the query
			$predicate = $_GET['predicate'];
			$author = $_GET['author'];
			
			//signing up datas to the session will be used during adding comments
			$_SESSION['predicate'] = $predicate;
			$_SESSION['author'] = $author;
		
			// Create an object
			$obj = new BlogObject($predicate);
			$obj->publisherID = $author;
		
			// Fetches the details
			$obj->getDetails();
		 
			// Give this to the view
			$this->result = $obj;
		
			// get author reputation
			$request = new Request("ReputationRequestHandler", READ);
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("producer",  $obj->publisherID);							
			$request->addArgument("consumer",  $_SESSION['user']->id);
		
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
		
			if (isset($responseObject->data->reputation)) {
				$value =  json_decode($responseObject->data->reputation) * 100;
			} else {
				$value = 100;
			}
			$this->reputation["author"] = $value;
		
			// Get Project reputation
			$request->addArgument("producer",  $predicate.$obj->publisherID);	
		
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
		
			if (isset($responseObject->data->reputation)) {
				$value =  json_decode($responseObject->data->reputation) * 100;
			} else {
				$value = 100;
			}
			
			// Store reputation value and n. of ratings
			$this->reputation["value"] = $value;
			$this->reputation["value_noOfRatings"] = $responseObject->dataObject->reputation->noOfRatings;
			
			//
			$this->search_comment();
			// Render the view
			$this->renderView("BlogDetails");
		
	}
	
	//searching comments
	public function search_comment() {
	
		// -- Search
		$search_comments = new BlogObject();
		$this->fillObj($search_comments);
		$this->result_comment = $search_comments->find();
	
	}
	
	private function fillObj($obj) {
	
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
	
	}
	
}
?>