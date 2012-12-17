<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class BlogController extends AuthenticatedController {
	
	public $result;
	public $reputationMap = array();

	public function handleRequest() {
		
		parent::handleRequest();
		print_r($_POST);
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
			// -- Publish
			$obj = new BlogObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->pred1 = $_SESSION['pred1'];
			$obj->publish();
			
			$this->success = "Published !";
			
		} elseif(isset($_GET['method']) && $_GET['method'] == "Search") {

			// -- Search
			$search = new BlogObject();
			$search->pred1 = $_GET['pred1'];
			$_SESSION['pred1'] = $search->pred1;
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
			
			$this->renderView("Blog");
			
		}elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {

			$obj = new BlogObject();				
			// Fill the object
			$this->fillObj($obj);
			$obj->publisherID = $_SESSION['user']->id;
			$obj->delete();			
			$this->result = $obj;	
			$this->renderView("Blog");
			$this->success = "Deleted !";	
		} 
		elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Subscribe") {
			
			// -- Subscribe
			$obj = new ExampleObject();
				
			// Fill the object
			$this->fillObj($obj);
			$obj->subscribe();
				
			$this->success = "Subscribe !";
		}
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
// 		$obj->begin = $_POST['begin'];
// 		$obj->end = $_POST['end'];
// 		$obj->wrapped1 = $_POST['wrapped1'];
// 		$obj->wrapped2 = $_POST['wrapped2'];
		
		$obj->pred1 = $_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
// 		$obj->data2 = $_POST['data2'];
// 		$obj->data3 = $_POST['data3'];
	}
 	
}
?>