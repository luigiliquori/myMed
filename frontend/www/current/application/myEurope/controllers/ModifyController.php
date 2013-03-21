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
		
		// Render the view
		$this->renderView("modify");
	}
}
?>