<?
class EditProfileController extends ExtendedProfileRequired {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		// Switch on profile type
		if ($this->extendedProfile instanceof ProfileBenevole) {
			$this->profileBenevole = $this->extendedProfile;
			$this->renderView("editProfileBenevole"); 
		} else {
			$this->profileAssociation = $this->extendedProfile;
			$this->renderView("editProfileAssociation");
		}
		
	}
	
} 
?>