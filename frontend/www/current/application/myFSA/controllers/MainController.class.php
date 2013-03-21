<?

/**
 *  Main controller 
 *  
 */
class MainController extends AuthenticatedController {
	

	public function handleRequest() {
		
		parent::handleRequest();
			
		if(!isset($_SESSION['ExtendedProfile']))
			$this->fetchExtendedProfile();
	
		$this->renderView("main");
	}
	
	public /*void*/ function fetchExtendedProfile(){
	
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
	
		if (!empty($result)){
			$_SESSION['ExtendedProfile'] = $result;
		}
	
	}
}
?>