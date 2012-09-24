<? 

require_once 'profile-utils.php';

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
		else if ($_SESSION['myEurope']->permission <= 0)
			$this->renderView("WaitingForAccept");
		
	}
	
	/**
	 * 
	 * @return multitype:unknown
	 */
	public /*void*/ function fetchExtendedProfile(){
		
		debug_r($_SESSION['user']);
		
		$user = new User($_SESSION['user']->id);
		try {
			$details = $user->read();
		} catch (Exception $e) {
			$this->showProfileList();
		}
		$_SESSION['myEurope'] = (object) $details;
		$profile = new Profile($details['profile']);
		try {
			$profile->details = $profile->read();
		} catch (Exception $e) {
			$this->showProfileList();
		}
		$profile->parseProfile();
		$profile->reputation = pickFirst(parent::reputation(null, $profile->details['id']));
		debug_r($profile->reputation);
		
		$_SESSION['myEuropeProfile'] =$profile;
		
		if ($_SESSION['myEurope']->permission <= 0){
			$this->renderView("WaitingForAccept");
		}
		
		$this->renderView("Main");
		
	}
	
	
	public function showProfileList(){
		
		$profile = new Profile();
		try {
			$res = $profile->search();
		} catch (Exception $e) {
		}
		
		$this->cats = Categories::$roles;
		
		function filterArray($array, $value){
			$result = array();
			foreach($array as $item) {
				if ($item->role == $value) {
					$result[] = $item;
				}
			}
			return $result;
		}
		
		foreach($this->cats as $k=>$v){
			$this->cats[$k] = filterArray($res, $k);
		}
		debug_r($this->cats);
		$this->renderView("ExtendedProfileCreate");
		
		
	}

}
?>