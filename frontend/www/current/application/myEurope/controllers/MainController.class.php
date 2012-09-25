<? 
class MainController extends ExtendedProfileRequired {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		debug_r($_SESSION['user']);
		debug_r($_SESSION['myEurope']);
		debug_r($_SESSION['myEuropeProfile']);
		$this->renderView("main");
		
	}
	
}
?>