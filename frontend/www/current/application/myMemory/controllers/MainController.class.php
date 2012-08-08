<? 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		/*
		 * Detect if the user is using a mobile device.
		 * This application will have different dehaviours for mobile.
		 */
		$m = new Mobile_Detect();
		$_SESSION['isMobile'] = $m->isMobile();
		//$_SESSION['isMobile'] = true;
		
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if( !isset($_SESSION['ExtendedProfile'])){
			debug("No Ext Profile, Fetching..");
			$this->fetchExtendedProfile();

		}
		else
			$this->renderView("Main");
		
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
			$this->renderView("Main");
		}

	}
	
	
}
?>