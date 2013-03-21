<? 

class ExtendedProfileRequired extends GuestController {
	
	public function handleRequest(){
		
		parent::handleRequest();

		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if (!isset($_SESSION['myEurope'], $_SESSION['myEurope']->permission)){
			$this->mapper = new DataMapper;
				
			$user = new User($_SESSION['user']->id);
			try {
				$usr = $this->mapper->findById($user);
			} catch (Exception $e) {
				//$this->setError($e->getMessage());
			}
			if (isset($usr)){
				$profile = new Profile($usr['profile']);
				try {
					$profile->details = $this->mapper->findById($profile);
				} catch (Exception $e) {
					$this->setError(_("Your organization profile could not be found, make sure to have a valid one in the profiles list section"));
				}
				if (isset($profile->details)){
					$profile->parseProfile();
					$profile->reputation = pickFirst(getReputation(array($profile->details['id'])));
					
					$_SESSION['myEurope'] = (object) array_merge( $usr, (array) $profile);
					
					if ($_SESSION['myEurope']->permission <= 0){
						// set as guest
						$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create');
					} else if ($_SESSION['myEurope']->permission == 1){
						$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create');
					} else {
						$_SESSION['acl'] = array('defaultMethod', 'read', 'delete', 'update', 'create', 'updatePermission');
					}
					
					debug('fetched');
				}
			}
		}
		
	}
	
	public function error($arguments) {
		debug('>>>>>>>>>> er');
		debug_r($_SESSION['myEurope']);
		if ($_SESSION['myEurope']->permission <= 0)
			$this->setError(_("Your profile is not yet validated by admins"));
		else if ($_SESSION['myEurope']->permission == 1)
			$this->setError(_("This feature is restricted to Admin users"));
		
		$this->forwardTo($this->name);
	}

}
?>