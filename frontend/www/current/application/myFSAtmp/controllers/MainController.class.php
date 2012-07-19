<? 
class MainController extends AuthenticatedController {

// 	//$this->renderView("Main");
// 	//$this->renderView("search");
// 	$this->redirectTo("publish");
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if( !isset($_SESSION['ExtendedProfile'])){
			$this->fetchExtendedProfile();
			


		}
		else
			$p = new PublishController();
			$p->search();
			//$this->renderView("search");
					
	}
	
	/**
	 * Fetch the user extendedProfile by using the static method getExtendedProfile of the class ExtendedProfile.
	 * In case the profile is found, it is stored in$_SESSION['ExtendedProfile'] and the Main view is called.
	 * Else, the ExtendedProfileNeeded viex is called.
	 * @param implicit : use the current User Id stored in the session.
	 * @see ExtendedProfile::getExtendedProfile()
	 */
	public /*void*/ function fetchExtendedProfile(){
	
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);

		if (empty($result)){
			$this->error = "";
			$this->renderView("ExtendedProfileNeeded");
		}
		else {
			$_SESSION['ExtendedProfile'] = $result;
			$this->success = "";
			$this->renderView("search");
		}

	}
	
// 	$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 	$debugtxt  .= var_export("bla", TRUE);
// 	$debugtxt .= "</pre>";
// 	debug($debugtxt);
	
}
?>