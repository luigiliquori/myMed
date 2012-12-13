<? 
class DetailsController extends ExtendedProfileRequired {
	
	public $reputation = array();
	
	public function handleRequest() {

		parent::handleRequest();
		
		/* Guest access provided */
		$id = rand(100000, 999999);
		$user = (object) array('id'=>'MYMED_'.$id, 'name'=>'user'.$id);
		$_SESSION['user'] = insertUser($user, null, true);
		$_SESSION['acl'] = array('defaultMethod', 'read');
		$_SESSION['user']->is_guest = 1;
		
		// Get arguments of the query
		$predicate = $_GET['predicate'];
		$author = $_GET['author'];
		
		// Create an object
		$obj = new Partnership($predicate);
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
		$this->reputation["author"] = $value;
		
		// get value reputation
		$request->addArgument("producer",  $predicate.$obj->publisherID);	
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
		$this->reputation["value"] = $value;
		
		// Render the view
		$this->renderView("details");
	}
}
?>