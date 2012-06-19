<? 
class MainController extends AuthenticatedController {
	

	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publish") {
			
			
			$obj = new ExampleObject();
			
			// Fill the object
			$this->fillObj($obj);
			$obj->publish();
			
			$this->success = "Published !";
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Search") {
			
			$search = new ExampleObject();
			$this->fillObj($search);
			$this->result = $search->find();
			
		} else {
			// Simply show the form
		}

		$this->renderView("main");
	}
	
	// Fill object with POSt values
	private function fillObj($obj) {
		$obj->begin = $_POST['begin'];
		$obj->end = $_POST['end'];
		$obj->data = $_POST['data'];
		
		$obj->pred1 = $_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		
		$obj->data1 = $_POST['data1'];
		$obj->data2 = $_POST['data2'];
		$obj->data3 = $_POST['data3'];
		
	}
 	
}
?>