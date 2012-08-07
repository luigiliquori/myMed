<?php 

define("BENEVOLE", "benevole");
define("ASSOCIATION", "association");
define("NICE_BENEVOLAT", "nice_benevolat");

include_once("GuestOrUserController.php");

/** Class that requires the user to fill out extended profile */
class ExtendedProfileRequired extends GuestOrUserController {
	
	/** 
	 *  @var $extendedProfile GenericDataBean 
	 *  The extended profile
	 */
	public $extendedProfile = null;
	
	/** "benevole" or "association" or "nice_benevolat" */
	public $profileType = null;
	
	
	public function __construct($profileType = null) {
		$this->profileType = $profileType;
	}
	
	public /*String*/ function handleRequest() {
		
		// We need to be authenticated first
		parent::handleRequest();
		
		// Look for extended profile is SESSION
		if (!array_key_exists(EXTENDED_PROFILE, $_SESSION)) {

			
			// Get the extended profile
			if ($this->user != null) {
				$extProfile = ExtendedProfileRequired::getExtendedProfile($this->user->id);
			}

			// Nothing found => redirect to create profile form
			if ($this->user == null || $extProfile == null) {
				
				$this->error = "Pour cette action, vous avez besoin d'un compte myBénévolat";
				if ($this->profileType != null) {
					$this->error .= " de type $this->profileType";
				}
				$this->forwardTo("extendedProfile:create", array("type" => $this->profileType));
					
			} else {
					
				// Set the extended profile (both in session and current controller)
				$_SESSION[EXTENDED_PROFILE] = $extProfile;
			}
			
		}
		
		// Set it in the controller
		$this->extendedProfile = $_SESSION[EXTENDED_PROFILE];
		
		// Check the type of profile
		switch ($this->profileType) {
			case BENEVOLE : 
				if (!$this->extendedProfile instanceof ProfileBenevole) $this->wrongProfileType(); 
				break;
			case ASSOCIATION :
				if (!$this->extendedProfile instanceof ProfileAssociation) $this->wrongProfileType();
				break;
			case NICE_BENEVOLAT :
				if (!$this->extendedProfile instanceof ProfileNiceBenevolat) $this->wrongProfileType();
				break;
		}
		
	}
	
	/** Get an extended profile from a user ID */
	static public function getExtendedProfile($id) {
		
		// Hard coded : Nice Benevolat
		if ($id == ProfileNiceBenevolat::$USERID) {
			return ProfileNiceBenevolat::getInstance();	
		}
		
		// Search for an association profile ?
		$profileAsso  = new ProfileAssociation();
		$profileAsso->userID = $id;
		$result = $profileAsso->find();
		
		// Not found => search for a Benevole profile
		if (sizeof($result) == 0) {
			$profilBenevole  = new ProfileBenevole();
			$profilBenevole->userID = $id;
			$result = $profilBenevole->find();
		}
		
		// Nothing found => null
		if (sizeof($result) == 0) return null;

		// Fetch details
		$result[0]->getDetails();
		
		return $result[0];
	}

	/** Extended profile found of wront type */
	public function wrongProfileType() {		
		if ($this->extendedProfile instanceof ProfileBenevole) {
			$this->actualProfileType = BENEVOLE;
		} else if ($this->extendedProfile instanceof ProfileNiceBenevolat)  {
			$this->actualProfileType = NICE_BENEVOLAT;
		} else {
			$this->actualProfileType = ASSOCIATION;
		}
		
		$this->renderView("wrongProfileType");
	}
	
	
}