<? 
class MainController extends ExtendedProfileRequired {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		$this->renderView("main");
		
	}
	
}
?>