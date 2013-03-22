<? 

/**
 * ExtendedProfileRequired try to get the User ExtendedProfile, if it
 * exists
 */

class ExtendedProfileRequired extends GuestController {
	
	/**
	 * HandleRequest
	 */
	public function handleRequest(){
		
		parent::handleRequest();

		// Try to get the User ExtendedProfile if it exist
		// ExtendedProfile stored in the $_SESSION while using the app
		if (!isset($_SESSION['myTemplateExtended'], $_SESSION['myTemplateExtended']->permission)) {
			
			// Search the user 
			$this->mapper = new DataMapper;
			$user = new User($_SESSION['user']->id);
			try {
				$usr = $this->mapper->findById($user);
			} catch (Exception $e) {
				// $this->setError($e->getMessage());
			}
			
			// The user is found
			if (isset($usr)) {
				
				// Search for user Extended Profile details
				$profile = new myTemplateExtendedProfile($usr['profile']);
				try {
					$profile->details = $this->mapper->findById($profile);
				} catch (Exception $e) {
					$this->setError(_("Your extended profile could not be found"));
				}
				
				// Get user Extended Profile details
				if (isset($profile->details)){
					
					$profile->parseProfile();
					$profile->reputation 
						= pickFirst(getReputation(array($profile->details['id'])));
					
					$_SESSION['myTemplateExtended'] = (object) array_merge( $usr, (array) $profile);
					
					// Set user Access Control Lists
					if ($_SESSION['myTemplateExtended']->permission <= 0){
						// Guest
						$_SESSION['acl'] = array('defaultMethod', 'read', 'update');
					} else if ($_SESSION['myTemplateExtended']->permission == 1){
						$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create');
					} else {
						$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create', 'updatePermission');
					}
				}
				
			} // ENDIF isset($usr)
			 
		} // ENDIF !isset($_SESSION['myTemplateExtended'], $_SESSION['myTemplateExtended']->permission
		
	}

}
?>