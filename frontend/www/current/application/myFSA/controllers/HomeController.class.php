<? 
class HomeController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		$this->renderView('home');				
	}
	
	
}
?>