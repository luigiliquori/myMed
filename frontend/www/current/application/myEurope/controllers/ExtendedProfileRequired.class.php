<? 

require_once 'profile-utils.php';

class ExtendedProfileRequired extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if (!array_key_exists('myEurope', $_SESSION)) {

			$this->fetchExtendedProfile();
		}
		else if ($_SESSION['myEurope']->permission <= 0)
			$this->renderView("WaitingForAccept");
		
	}
	
	
	public $cats = array(
			"Association - Coopérative - Mutuelle"=>"",
			"Entreprise privée"=>"",
			"Chambre consulaire - Groupement professionnel"=>"",
			"Université - Recherche"=>"",
			"Mairie - établissement public communal ou intercommunal"=>"",
			"Département - établissement public départemental"=>"",
			"Région - établissement public régional"=>"",
			"Service de l’Etat - établissement public de l’Etat"=>"",
			"Autre établissement ou groupement public"=>"",
			"Autre"=>""
	);
	
	/**
	 * 
	 * @return multitype:unknown
	 */
	public /*void*/ function fetchExtendedProfile(){
		
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id));
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){}
		
		if (empty($res->details)){
			$this->error = "";
			debug("wow");

			$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":profiles", "predicates"=>array()));
			
			try{
				$res = $find->send();
			}
			catch(Exception $e){}
			if (isset($res)){
				debug('there');
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
					$this->cats[$k] = filterArray($res->results, $k);
				}
			}
			
			$this->renderView("ExtendedProfileCreate");
		}
		else {
			
			$_SESSION['myEurope'] = $res->details;
			$this->success = "";
			
			$profile = getProfile($this, $_SESSION['myEurope']->profile);
			if (!empty($profile))
				$_SESSION['myEuropeProfile'] =$profile;
			else
				$this->renderView("ExtendedProfileCreate");
			
			if ($_SESSION['myEurope']->permission <= 0)
				$this->renderView("WaitingForAccept");
			$this->renderView("Main");
		}
	}

}
?>