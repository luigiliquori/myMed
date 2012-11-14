<? 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if( !isset($_SESSION['ExtendedProfile'])){
			$this->fetchExtendedProfile();
			$this->redirectTo("ExtendedProfile");
			
		}
		else{
			
			$this->renderView(main);
			
		}

					
	}
	

	public /*void*/ function fetchExtendedProfile(){
		
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);


		
		if (empty($result)){
			$this->error = "";
			$this->renderView("ExtendedProfileNeeded");
		}
		else {
			$_SESSION['ExtendedProfile'] = $result;
			$this->success = "";
			//$this->redirectTo("search");
			$this->renderView(main);
		}

	}
	
	
}
?>