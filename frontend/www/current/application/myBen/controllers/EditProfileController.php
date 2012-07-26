<?
class EditProfileController extends ExtendedProfileRequired {

	public function handleRequest() {

		// Parent handler
		parent::handleRequest();

		// Submit => update profile
		if (in_request("submit")) {

			// Type of profile
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
			$profile->publisherID = $this->user->id;

			// Populate all except userID
			$profile->populateFromRequest(
					array("userID"));

			// Update the profile (delete the old one)
			$this->extendedProfile->delete();
			$profile->publish();
			$this->extendedProfile = $profile;
			$_SESSION[EXTENDED_PROFILE] = $profile;
			
			// Fill success message
			$this->setSuccess("Profile mis à jour");
		}
		
		// Render the "edit" view again

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