<? 
class MainController extends ExtendedProfileRequired {
	
	function handleRequest(){
	
		parent::handleRequest();
	}
	
	function defaultMethod(){
		
		$this->renderView("main");
		
	}

	
}
?>