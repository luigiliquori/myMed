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

		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id));
		
		try{
			$res = $req->send();
		}
		catch(Exception $e){
			//return null;
			$this->details=new StdClass();
			$this->details->text = "Contenu effacé par l'auteur";
		}

		if (isset($res->details)){
			$this->details = $res->details;
			$rep =  new Reputationv2($this->id);
			$this->reputation = $rep->send();
			
			if (isset($this->details->user)){
				$this->details->userProfile = $this->getProfile($this->details->user);
			}
			$this->partnersProfiles = array();

			foreach ($this->details as $k => $v){
				if (strpos($k, "tempPartner") === 0){
					$p = $this->getProfile($v);
					array_push($this->partnersProfiles, $p);
				}
			}
			debug_r($this->details);
			$this->renderView("details");
		} else
			$this->renderView("details");
			//$this->redirectTo("search");
		// @todo errors
		
		// Render the view
		
	}
	
	
	public /*void*/ function delData(){
		
		$publish = new RequestJson($this, array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id']), DELETE);
		
		debug('trying to delete '.$_GET['namespace']."..".$_GET['id']);

		$publish->send();
	
	
	}
	
	public /*void*/ function addTempPartner(){
		$data = array(
			"tempPartner".$_SESSION['user']->id=>$_SESSION['user']->id
		);
		$publish = new RequestJson( $this, 
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id'], "data"=>json_encode($data) ),
				UPDATE);
	
		$publish->send();
	}
	
	public /*void*/ function getProfile($id){
	
		$find = new RequestJson( $this, array("application"=>APPLICATION_NAME.":users","id"=>$id));
			
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
	
		if (!empty($result)){
	
			$profile = $result->details;	
			$rep =  new Reputationv2($id);
			$profile->reputation = $rep->send();
			debug_r($profile);
			return $profile;
		}
		return null;
	
	}
}
?>