<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class PublishController extends AuthenticatedController {
	

	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
			
			// -- Publish

			
			$obj = new PublishObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->success = "Published !";
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
			// -- Search
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();
			
			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
			$debugtxt  .= var_export($this->result, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
			$this->renderView("results");
			
		} else {
			
			// -- Show the form
		}

		$this->renderView("publish");
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		$obj->begin = $_POST['begin'];
		$obj->end = $_POST['end'];
		$obj->wrapped1 = $_POST['wrapped1'];
		$obj->wrapped2 = $_POST['wrapped2'];
		
		$obj->pred1 = "FSApublication";
// 		$obj->pred2 = $_POST['pred2'];
// 		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
		$obj->data2 = $_POST['data2'];
		$obj->data3 = $_POST['data3'];
		
	}
 	
}
?>