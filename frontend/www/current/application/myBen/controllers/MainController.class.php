<? 
class MainController extends ExtendedProfileRequired {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->renderView("main");
		
	}
 	
}
?>