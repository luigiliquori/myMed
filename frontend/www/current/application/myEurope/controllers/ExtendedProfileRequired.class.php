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
		
		$rep =  new Reputationv2($_SESSION['user']->id);
		$myrep = $rep->send();
		
		$find = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, array("id"=>$_SESSION['user']->id),
				"users", $this);
			
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
			//organize projects
			$partnerships = array();
			foreach ($result as $k=>$v){
				if (strpos($k, "part") === 0){
					array_push($partnerships, $v);
				}
			}
			$_SESSION['myEuropeProfile'] = (object) $result;
			$this->success = "";
			$_SESSION['myEuropeProfile']->reputation = $myrep;
			$_SESSION['myEuropeProfile']->partnerships = $partnerships;
			
			if ($_SESSION['myEuropeProfile']->permission <= 0)
				$this->renderView("WaitingForAccept");
			$this->renderView("Main");
		}
	}
	
	
}
?>