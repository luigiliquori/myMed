<? 
class ExtendedProfileRequired extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if (!array_key_exists('myEuropeProfile', $_SESSION)) {

			$this->fetchExtendedProfile();
		}
		else if ($_SESSION['myEuropeProfile']->permission <= 0)
			$this->renderView("WaitingForAccept");
		
	}
	
	/**
	 * Fetch the user extendedProfile by using the static method getExtendedProfile of the class ExtendedProfile.
	 * In case the profile is found, it is stored in$_SESSION['ExtendedProfile'] and the Main view is called.
	 * Else, the ExtendedProfileNeeded viex is called.
	 * @param implicit : use the current User Id stored in the session.
	 * @see ExtendedProfile::getExtendedProfile()
	 */
	public /*void*/ function fetchExtendedProfile(){
		
		$find = new DetailRequestv2($this, "users", $_SESSION['user']->id);
			
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		
		if (empty($result)){
			$this->error = "";
			$this->renderView("ExtendedProfileCreate");
		}
		else {
		
			$_SESSION['myEuropeProfile'] = new ExtendedProfile($result);
			$this->success = "";
			
			if ($_SESSION['myEuropeProfile']->permission <= 0)
				$this->renderView("WaitingForAccept");
			$this->renderView("Main");
		}
	}
	
	
}
?>