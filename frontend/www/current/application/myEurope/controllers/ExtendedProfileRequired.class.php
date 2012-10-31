<? 

class ExtendedProfileRequired extends AuthenticatedController {
	
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
				//ignore the error for the moment
			}

			$profile = new Profile($usr['profile']);
			try {
				$profile->details = $this->mapper->findById($profile);
			} catch (Exception $e) {
			}
			$profile->parseProfile();
			$profile->reputation = pickFirst(parent::getReputation(array($profile->details['id'])));
				
			$_SESSION['myEurope'] = (object) array_merge( $usr, (array) $profile);

			if ($_SESSION['myEurope']->permission <= 0){
				// set as guest
				$_SESSION['user']->acl = array('defaultMethod', 'read');
			} else if ($_SESSION['myEurope']->permission == 1){
				$_SESSION['user']->acl = array('defaultMethod', 'read', 'delete', 'update', 'create');
			} else {
				$_SESSION['user']->acl = array('defaultMethod', 'read', 'delete', 'update', 'create', 'updatePermission');
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