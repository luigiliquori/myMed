<? 
class ModifyController extends ExtendedProfileRequired {
	
	
	public function handleRequest() {

		parent::handleRequest();
		
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
		$this->renderView("modify");
	}
}
?>