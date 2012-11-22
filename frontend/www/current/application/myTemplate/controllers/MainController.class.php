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
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
			// -- Search
			$search = new ExampleObject();
			$this->fillObj($search);
			$this->result = $search->find();
			
			// get userReputation
			foreach($this->result as $item) :
			
				// Get the reputation of the user in each application
				$request = new Request("ReputationRequestHandler", READ);
				$request->addArgument("application",  APPLICATION_NAME);
				$request->addArgument("producer",  $item->publisherID);		
				$request->addArgument("consumer",  $_SESSION['user']->id);
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if (isset($responseObject->data->reputation)) {
					$value =  json_decode($responseObject->data->reputation) * 100;
				} else {
					$value = 100;
				}
				$this->reputationMap[$item->publisherID] = $value;
			
			endforeach;
			
			$this->renderView("results");
			
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