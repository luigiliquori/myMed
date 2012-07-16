<?php 


/** Delete extended profile */
class DeleteProfileController extends ExtendedProfileRequired {
	
	public /*String*/ function handleRequest() {
		
		debug("Delete profile");
		
		// We need to be authenticated first
		parent::handleRequest();
		
		// Delete the extended profile
		$this->extendedProfile->delete();
		
		// Remove it from SESSION
		unset($_SESSION[EXTENDED_PROFILE]);
		
		$this->redirectTo("main");
		
	}
}