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
				
			$diseaseLevel = "";
			$careGiver = "";
			$doctor = "";
			$callingList = "";
			
			foreach ($result as $line){
				switch($line->key){
					
					case "home" :
						$home = json_decode($line->value, TRUE);
						break;
					case "callingList" :
						$callingList = json_decode($line->value, TRUE);
						break;
					case "careGiver" :
						$careGiver = json_decode($line->value, TRUE);
						break;
					case "doctor" :
						$doctor = json_decode($line->value, TRUE);
						break;
					case "diseaseLevel" :
						$diseaseLevel = json_decode($line->value, TRUE);
						break;
				}
				if ($line->key ="callingList"){
						
				}
				
			}
			
			$extendedProfile = new ExtendedProfile($_SESSION['user']->id, $home, $diseaseLevel, $careGiver, $doctor, $callingList);
			$_SESSION['ExtendedProfile'] = $extendedProfile;
			$this->success = "";
			$this->renderView("Main");
		}

	}
	
	
}
?>