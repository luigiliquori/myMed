<? 
class MainController extends ExtendedProfileRequired {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		debug_r($_SESSION['user']);
		$this->renderView("main");
		
	}
	
}
?>