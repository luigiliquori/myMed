<? 


class DetailsController extends AuthenticatedController {
	
	public $delete = false;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		$this->id = $_GET['id'];
		
		if (isset($_GET["rm"])){
			$this->delData();
		}
		if (isset($_GET["partnerRequest"])){
			$this->addTempPartner();
		}
		
		// put
		if (isset($_GET["accept"])){
			$this->addPartner();
		}
		
		$this->namespace = "part"; //temp

		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id));
		
		try{
			$res = $req->send();
		}
		catch(Exception $e){}

		if (isset($res->details)){
			$this->details = $res->details;
			
			//$this->reputation = pickFirst(parent::reputation($this->details->user, $this->id));
			$this->reputation = pickFirst(parent::getReputation(array(APPLICATION_NAME), array($this->details->user)));
			
			if (isset($this->details->user)){
				
				$this->details->userProfile = $this->getProfile($this->details->user);
				
				if ($this->delete){
					$request = new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles", "id"=>$this->details->partner, "field"=>"part_".$this->id),
							DELETE);
					$request->send();
				}
			}
			
			$this->partnersProfiles = array();
			
			foreach ($this->details as $k => $v){
				if (strpos($k, "user_") === 0){
					$p = $this->getProfile($v);
					$this->partnersProfiles[$p->id]= $p;
				}
			}
			if (isset($this->details->userProfile))
				unset($this->partnersProfiles[$this->details->userProfile->id]);
			
			if ($this->delete){
				$request = new RequestJson($this,
						array("application"=>APPLICATION_NAME.":profiles", "id"=>"", "field"=>"part_".$this->id),
						DELETE);
				
				foreach ($this->partnersProfiles as $v){
					$req->addArguments(array("id"=>$v->id));
					$request->send();
				}
			}
			
			$this->renderView("details");
		} else{
			$this->details=new StdClass();
			$this->details->text = "<h2 style='text-align:center;'>Contenu effacé par l'auteur</h2>";
			$this->details->title = "effacé";
			$this->renderView("details");
		}
			
			//$this->redirectTo("search");
		// @todo errors
		
		// Render the view
		
	}
	
	
	public /*void*/ function delData(){
		
		$publish = new RequestJson($this, array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id']), DELETE);
		
		debug('trying to delete '.$_GET['namespace']."..".$_GET['id']);

		$publish->send();
		
		//delete from partners profiles after read
		$this->delete = true;
	
	}
	
	public /*void*/ function addTempPartner(){
		$data = array(
			"tempPartner".$_SESSION['user']->id=>$_SESSION['user']->id
		);
		$publish = new RequestJson( $this, 
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$_GET['id'], "user"=> $_SESSION['user']->id, "user"=>$_SESSION['user']->id, "data"=>$data ),
				UPDATE);
	
		$rs = $publish->send();
		if (empty($this->error))
			$this->success = _("Partnership request sent");
		
		debug_r($rs);
		debug_r($this->success);
	}
	
	public /*void*/ function addPartner(){

		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id, "field"=>"tempPartner".$_GET["accept"] ),
				DELETE);
		$publish->send();
		
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id, "user"=>"noNotification", "data"=>array("user_".$_GET["accept"]=>$_GET["accept"]) ),
				UPDATE);
		$publish->send();
		
		
		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":users","id"=>$_GET["accept"]));
		try{
			$res = $req->send();
		}
		catch(Exception $e){}
		if (isset($res->details)){
			
			$publish = new RequestJson( $this,
					array("application"=>APPLICATION_NAME.":profiles", "id"=>$res->details->profile, "data"=>array("part_".$this->id=>$this->id) ),
					UPDATE);
			$rs = $publish->send();
			if (empty($this->error))
				$this->success = _("Partner added");
			debug_r($rs);
			debug_r($this->error);
		}
		
	}

	
	public /*void*/ function getProfile($id){
	
		$user = new User($id);
		try {
			$details = $user->read();
		} catch (Exception $e) {
		}
		$profile = new Profile($details['profile']);
		try {
			$profile->details = $profile->read();
		} catch (Exception $e) {
		}
		$profile->parseProfile();
		$profile->reputation = pickFirst(parent::getReputation(array(APPLICATION_NAME), array($id)));
		return $profile;
	}
	
	
}
?>