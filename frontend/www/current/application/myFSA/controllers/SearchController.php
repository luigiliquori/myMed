<? 

/**
 *  This controller shows the search form and receives "search" queries.
 *  It renders the views "main" or "results".
 */
class SearchController extends AuthenticatedController {
	
	public function handleRequest() {
	
		parent::handleRequest();
			
		if(true) {
	
			// -- Search
			$this->search();	
			$this->renderView("search");
			
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
		
		//if isset pred2 && pred3 it means someone used searching advanced
		if(isset($_POST['pred2'])&&isset($_POST['pred3'])){
			
			$obj->pred2 = $_POST['pred2'];
			$obj->pred3 = $_POST['pred3'];			
		} 
		
		//otherwise is displaying all publications
		else {
			
			$obj->pred1 = "FSApublication";
		}
	
	}
 	
}
?>