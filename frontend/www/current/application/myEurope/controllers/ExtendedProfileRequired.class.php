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
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id));
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){}
		debug_r($res);
		if (!isset($res->details)){
			$this->error = "";
			debug("wow");
			
			$this->showProfileList();
		}
		else {
			
			$_SESSION['myEurope'] = $res->details;
			$this->success = "";
			
			$profile = getProfile($this, $_SESSION['myEurope']->profile);
			debug('test');
			debug_r($profile);
			if (isset($profile->id))
				$_SESSION['myEuropeProfile'] =$profile;
			else
				$this->showProfileList();
			
			if ($_SESSION['myEurope']->permission <= 0)
				$this->renderView("WaitingForAccept");
			$this->renderView("Main");
		}
	}
	
	
	public function showProfileList(){
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":profiles", "predicates"=>array()));
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){
		}
		if (isset($res->results)){
			function filterArray($array, $value){
				$result = array();
				foreach($array as $item) {
					if ($item->role == $value) {
						$result[] = $item;
					}
				}
			
				return $result;
			}

			$this->cats = Categories::$roles;
			
			foreach($this->cats as $k=>$v){
				$this->cats[$k] = filterArray($res->results, $k);
			}
			debug_r($this->cats);
			$this->renderView("ExtendedProfileCreate");
		}
		$this->renderView("Main");
		
	}

}
?>