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
			$debugtxt  =  "<pre>DAAAAAAAAAAAAAAAATAAAAAAAAAAAAAAA";
			$debugtxt  .= var_export(time(), TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
			$this->redirectTo("search");
			
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
			$this->redirectTo("search");
		}

	}
	
	
}
?>