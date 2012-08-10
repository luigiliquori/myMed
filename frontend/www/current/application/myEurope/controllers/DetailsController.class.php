<? 
class DetailsController extends AuthenticatedController {
	
	public function handleRequest() {
		
		
		
		parent::handleRequest();
		
		if (isset($_GET["rm"])){
			$this->delData();
		}
		if (isset($_GET["partnerRequest"])){
			$this->addTempPartner();
		}
		 
		$this->id = $_GET['id'];
		$this->text = "description vide";
		
		debug('details of '.$this->id);
		
		$req = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, array("id"=>$this->id),
				$_GET['namespace'], $this);
		
		try{
			$this->details = $req->send();
		}
		catch(Exception $e){
			//return null;
			$this->details=new StdClass();
			$this->details->text = "Contenu effacé par l'auteur";
		}

		if (isset($this->details)){
			if (isset($this->details->user)){
				$this->details->userProfile = $this->getProfile($this->details->user);
			}
			$this->partnersProfiles = array();
			$a = (array) $this->details;
			foreach ($a as $k => $v){
				if (strpos($k, "tempPartner") === 0){
					$p = $this->getProfile($v);
					array_push($this->partnersProfiles, $p);
				}
			}
			
			$this->renderView("details");
		} else
			$this->redirect("search");
		// @todo errors
		
		// Render the view
		
	}
	
	
	public /*void*/ function delData(){
	
	
		$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", DELETE, array("id"=>$_GET['id']),
				$_GET['namespace'], $this);
		
		debug('trying to delete '.$_GET['namespace']."..".$_GET['id']);

		$publish->send();
	
	
	}
	
	public /*void*/ function addTempPartner(){
		$data = array(
			"tempPartner".$_SESSION['user']->id=>$_SESSION['user']->id
		);
		$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", UPDATE, array("id"=>$_GET['id'], "data"=>json_encode($data) ),
				$_GET['namespace'], $this);
	
		$publish->send();
	}
	
	public /*void*/ function getProfile($id){
	
		$find = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, array("id"=>$id),
				"users", $this);
			
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
	
		if (!empty($result)){
	
			$profile = (object) $result;	
			$rep =  new Reputationv2($id);
			$profile->reputation = $rep->send();
			debug_r($profile);
			return $profile;
		}
		return null;
	
	}
}
?>