<? 

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class BlogController extends AuthenticatedController {
	
	public $result;
	public $reputationMap = array();
	public $cathegory;

	public function handleRequest() {
		
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {

			// -- Publish
			$obj = new BlogObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			header("location: index.php?action=blog&method=Search&cathegory=".$_POST['pred1']);
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {			
			
			// -- Search
			$search = new BlogObject();
			
			//cathegory is taken from the header and all signs "_" are replaced for space
			$this->cathegory = $_GET['cathegory'];
			$cathegory = str_replace("_"," ",$this->cathegory);
			
			//object is filled only by one field cathegory which is first predicate
			$search->pred1 = $this->cathegory;
			
			//we obtain list of the topic
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
			
			$this->renderView("BlogResult");
			
		}elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {
			//deleting publication
			$obj = new BlogObject();				
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publisherID = $_SESSION['user']->id;
			$obj->delete();			
			
			//TODO
			//deleting coments
			
			header("location: index.php?action=blog&method=Search&cathegory=".$_POST['pred1']);
		} 
		elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {
			// -- Publish
				
			$obj = new BlogObject();
			
			// Fill the object
			$this->fillObj_comments($obj);
			$obj->publish();
				
			$this->result = $obj;
			header("location: index.php?action=blogDetails&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
		}

		$this->renderView("main");
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		$obj->begin = time();
		$obj->wrapped1 = $_POST['wrapped1'];
		$obj->wrapped2 = $_POST['wrapped2'];
		
		$obj->pred1 = $_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
		$obj->data2 = $_POST['data2'];
		$obj->data3 = $_POST['data3'];
	}
	
	private function fillObj_comments($obj) {
		$time = time();
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
		$obj->pred2 = $time;
		$obj->wrapped1 =$_POST['wrapped1'];
		$obj->wrapped2 =$_SESSION['user']->profilePicture;
	
	}
 	
}
?>