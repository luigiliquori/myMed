<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class MainController extends AuthenticatedController {
	
	public $result;
	public $reputationMap = array();

	public function handleRequest() {
		
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
			
			// -- Publish
			$obj = new ExampleObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->success = "Published !";
			
		} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {

			$obj = new ExampleObject();				
			// Fill the object
			$this->fillObj($obj);
			$obj->publisherID = $_SESSION['user']->id;
			$obj->delete();			
			$this->result = $obj;	
			$this->success = "Deleted !";	
		} else if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Subscribe") {
			
			// -- Subscribe
			$obj = new ExampleObject();
				
			// Fill the object
			$this->fillObj($obj);
			$obj->subscribe();
				
			$this->success = "Subscribe !";
		}

		$this->renderView("main");
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		$obj->begin = $_POST['begin'];
		$obj->end = $_POST['end'];
		$obj->wrapped1 = $_POST['wrapped1'];
		$obj->wrapped2 = $_POST['wrapped2'];
		
		$obj->pred1 = $_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
		$obj->data2 = $_POST['data2'];
		$obj->data3 = $_POST['data3'];
	}
 	
}
?>