<? 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		if(!$_SESSION['ExtendedProfile'])
			$this->fetchExtendedProfile();
		
		$this->renderView('main');				
	}
	
	public /*void*/ function fetchExtendedProfile(){
	
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
	
		if (!empty($result)){
			$_SESSION['ExtendedProfile'] = $result;
		}
	
	}
}
?>