<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class SearchController extends AuthenticatedController {
	
	public function handleRequest() {
	
		parent::handleRequest();
			
		if(true) {
			
			//this field is to get info if DetailsView is redirect from publish controller or details controller
			$_SESSION['controller'] = "Search";
	
			// -- Search
			$this->search();	
			$this->renderView("main");
			
		} 
	}
	
	public function search() {
	
			// -- Search
	
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();			
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		if(isset($_POST['pred2'])&&isset($_POST['pred3'])){
			$obj->pred2 = $_POST['pred2'];
			$obj->pred3 = $_POST['pred3'];
			
			$debugtxt  =  "<pre>I am in the fillObj1 second if";
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
		} 
		else {
			$obj->pred1 = "FSApublication";
		}
	
	}
 	
}
?>