<? 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		//if( !isset($_SESSION['Publish'])){
			
			$this->fetchPublication();


		//else
			//$this->renderView("Main");
			//$this->renderView("search");
			//$this->redirectTo("search");
		
	}
	
	/**
	 * Fetch the user extendedProfile by using the static method getExtendedProfile of the class ExtendedProfile.
	 * In case the profile is found, it is stored in$_SESSION['ExtendedProfile'] and the Main view is called.
	 * Else, the ExtendedProfileNeeded view is called.
	 * @param implicit : use the current User Id stored in the session.
	 * @see ExtendedProfile::getExtendedProfile()
	 */
	public /*void*/ function fetchPublication(){
		
		$result = Publish::getPublication($this, $_SESSION['user']->id);
		
		if (empty($result)){
			$this->error = "";
			$this->renderView("Publish");
		}
		else {
			$key1 = "";
			$key2 = "";
			$key3 = "";
			$publication = "";
			
			
			foreach ($result as $line){
				switch($line->key){
					
					case "key1" :
						$key1 = json_decode($line->value, TRUE);
						break;
					case "key2" :
						$key2 = json_decode($line->value, TRUE);
						break;
					case "key3" :
						$key3 = json_decode($line->value, TRUE);
						break;
					case "publication" :
						$publication = json_decode($line->value, TRUE);
						break;
				}
				
			}
			$publication = new Publish($_SESSION['user']->id, $key1, $key2, $key3, $publication);
			$_SESSION['Publish'] = $publication;
			$this->success = "";
			$this->renderView("Search");
		}

	}
	
	
}
?>