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
		
		$req = new SimpleRequestv2( array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id),
				"v2/DataRequestHandler", READ, $this);
		
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
			$a = (array) $this->details;
			foreach ($a as $k => $v){
				if (strpos($k, "tempPartner") === 0){
					$p = $this->getProfile($v);
					array_push($this->partnersProfiles, $p);
				}
			}
			
			$this->renderView("details");
		} else
			$this->renderView("details");
			//$this->redirectTo("search");
		// @todo errors
		
		// Render the view
		
	}
	
	
	public /*void*/ function delData(){
	
	
		$publish = new SimpleRequestv2(array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id']),
				"v2/DataRequestHandler", DELETE, $this);
		
		debug('trying to delete '.$_GET['namespace']."..".$_GET['id']);

		$publish->send();
	
	
	}
	
	public /*void*/ function addTempPartner(){
		$data = array(
			"tempPartner".$_SESSION['user']->id=>$_SESSION['user']->id
		);
		$publish = new SimpleRequestv2( 
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id'], "data"=>json_encode($data) ),
				"v2/DataRequestHandler", UPDATE, $this);
	
		$publish->send();
	}
	
	public /*void*/ function getProfile($id){
	
		$find = new SimpleRequestv2( array("application"=>APPLICATION_NAME.":users","id"=>$id),
				"v2/DataRequestHandler", READ, $this);
			
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