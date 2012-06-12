<? 
// Just show the view 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		if( !isset($_SESSION['ExtendedProfile']) ){
			
			$this->renderView("ExtendedProfileNeeded");
		}
		else
			$this->renderView("Main");
		
	}
	
	
}
?>