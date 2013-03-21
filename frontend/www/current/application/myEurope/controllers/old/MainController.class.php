<? 

include 'Mobile_Detect.php';

class MainController extends ExtendedProfileRequired {
	
	public $detect;
	
	function handleRequest(){
	
		parent::handleRequest();
	}
	
	function defaultMethod(){

		$this->detect = new Mobile_Detect();
		$this->renderView("main");
		
	}

	
}
?>