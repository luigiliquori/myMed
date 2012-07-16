<? 

/** Called after filling extended profile */
class FillProfileController extends AuthenticatedController {
	
	public function handleRequest() {
		
		// Authentication required
		parent::handleRequest();
		
		// type of profile
		$type = $_REQUEST['type'];
		
		// Switch on profile type
		if ($type == "benevole") {
			
			// Create a benevole profile
			$profile = new ProfileBenevole();
	
		} else if ($type == "association") {
			
			// Create a assoication profile
			$profile = new ProfileAssociation();
			
		} else {
			throw new Exception("Bad profile type : '$type'");
		}
		
		// Populate it
		$profile->userID = $this->user->id;
		
		// Populate all except userID
		$profile->populateFromRequest(
				array("userID"));
			
		// Save it
		$profile->publish();
		
		$this->redirectTo("main");
		
	}
}

?>