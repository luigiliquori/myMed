<? 
class DetailsController extends AuthenticatedController {
	
	public function handleRequest() {
		
		
		
		parent::handleRequest();
		
		$this->id = $_GET['id'];
		
		if (isset($_GET["rm"])){
			$this->delData();
		}
		if (isset($_GET["partnerRequest"])){
			$this->addTempPartner();
		}
		if (isset($_GET["accept"])){
			$this->addPartner();
		}
		
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
				if (strpos($k, "partner") === 0){
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
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id'], "user"=> $_SESSION['user']->id, "user"=>$_SESSION['user']->id, "data"=>$data ),
				UPDATE);
	
		$publish->send();
		if (empty($this->error))
			$this->success = _("Partnership request sent");
	}
	
	public /*void*/ function addPartner(){

		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id, "field"=>"tempPartner".$_GET["accept"] ),
				DELETE);
		$publish->send();
		
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id, "user"=>"noNotification", "data"=>array("partner".$_GET["accept"]=>$_GET["accept"]) ),
				UPDATE);
		$publish->send();
		
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_GET["accept"], "data"=>array("part".$this->id=>$this->id) ),
				UPDATE);
		$publish->send();
		
		if (empty($this->error))
			$this->success = _("Partnerships added");
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