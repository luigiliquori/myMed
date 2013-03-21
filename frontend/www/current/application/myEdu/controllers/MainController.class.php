<?

/**
 *  Main controller 
 *  
 */
class MainController extends ExtendedProfileRequired {
	
	public $result;
	public $reputationMap = array();

	public function handleRequest() {
		
		parent::handleRequest();
			
		if(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
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
		
		debug("PROFILE REQUEST");
		$request = new Requestv2("v2/ProfileRequestHandler", READ , array("userID"=>$_SESSION['user']->id));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		debug_r($responseObject->dataObject);

		$this->renderView("main");
	}
	
	// Fill User Publication object with POST values
	private function fillPublicationObj($obj) {
		
		// Publisher ID
		$obj->publisher = $_POST['publisher'];
		// Area
		$obj->area = $_POST['area'];
		// Category
		$obj->category = $_POST['category'];
		// Expiration date
		$obj->end 	= $_POST['date'];
		// Title
		$obj->title = $_POST['title'];
		// Publication text
		$obj->text 	= $_POST['text'];
	}
 	
}
?>