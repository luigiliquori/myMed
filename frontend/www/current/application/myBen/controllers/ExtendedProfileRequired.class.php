<?php 

define("EXTENDED_PROFILE", "extendedProfile");
define("BENEVOLE", "benevole");
define("ASSOCIATION", "association");
define("NICE_BENEVOLAT", "nice_benevolat");


/** Class that requires the user to fill out extended profile */
class ExtendedProfileRequired extends AuthenticatedController {
	
	/** 
	 *  @var $extendedProfile GenericDataBean 
	 *  The extended profile
	 */
	public $extendedProfile = null;
	
	/** "benevole" or "association" or "nice_benevolat" */
	public $profileType;
	
	
	public function __construct__($profileType) {
		$this->profileType = $profileType;
	}
	
	public /*String*/ function handleRequest() {
		
		// We need to be authenticated first
		parent::handleRequest();
		
		// Look for extended profile is SESSION
		if (!array_key_exists(EXTENDED_PROFILE, $_SESSION)) {

			// Search for an association profile ?
			$profileAsso  = new ProfileAssociation();
			$profileAsso->userID = $this->user->id;
			$result = $profileAsso->find();

			// Not found => search for a Benevole profile
			if (sizeof($result) == 0) {
				$profilBenevole  = new ProfileBenevole();
				$profilBenevole->userID = $this->user->id;
				$result = $profilBenevole->find();
			}

			// Nothing found => redirect to fillprofile
			if (sizeof($result) == 0) {
					
				// Create empty profiles
				$this->profileBenevole = new ProfileBenevole(); 
				$this->profileBenevole->disponibilites = array_keys(CategoriesDisponibilites::$values);
				$this->profileBenevole->missions = array_keys(CategoriesMissions::$values);
				
				$this->profileAssociation = new ProfileAssociation();
				$this->profileAssociation->missions = array_keys(CategoriesMissions::$values);
				
				$this->renderView("createProfile");
					
			} else {
					
				// Get extra data
				$result[0]->getDetails();
					
				// Set the extended profile (both in session and current controller)
				$_SESSION[EXTENDED_PROFILE] = $result[0];
			}
			
		}
		
		// Set it in the controller
		$this->extendedProfile = $_SESSION[EXTENDED_PROFILE];
		
	}
}