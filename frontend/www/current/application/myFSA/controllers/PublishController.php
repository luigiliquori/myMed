<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class PublishController extends AuthenticatedController {
	
	
// 	$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 	$debugtxt  .= var_export($this->result, TRUE);
// 	$debugtxt .= "</pre>";
// 	debug($debugtxt);
	
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
				
			// -- Publish
			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
			$debugtxt  .= var_export($_SESSION, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
				
			$obj = new PublishObject();
				
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->result = $obj;
			$this->result->publisherID = $_SESSION['user'];
			$this->renderView("details");
				
		}	elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
	
			// -- Search
			$this->search();	
		} 
		else {
				
			// -- Show the form
		}
	
		$this->renderView("publish");
	}
	
	public function search() {
	
			// -- Search
	
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();
	
			$this->renderView("search");
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		$obj->begin = $_POST['begin'];
		$obj->end = $_POST['end'];
 		$obj->wrapped1 ="";
		$obj->wrapped2 ="";
	
		$obj->pred1 = "FSApublication";
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
	
		$obj->data1 = $_POST['data1'];
 		$obj->data2 = "";
		$obj->data3 = "";
	
	}
	
 	
}
?>