<? 

class DetailsController extends ExtendedProfileRequired {
	
	private $delete = false;
	
	function handleRequest() {
		parent::handleRequest();
		$this->id = $_GET['id'];
		$this->namespace = "part"; //temp
	}
	
	function delete(){
		$this->delData();
		$this->forwardTo("details", array('id'=>$this->id));
	}
	
	function update(){
		if (isset($_GET["partnerRequest"])){
			$this->addTempPartner();
		}
		if (isset($_GET["accept"])){
			$this->addPartner();
		}
		$this->forwardTo("details", array('id'=>$this->id));
	}
		
	function defaultMethod() {
		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id));
		try{
			$res = $req->send();
		}
		catch(Exception $e){}

		if (isset($res->details)){
			$this->details = $res->details;
			
			$this->tags = $this->format($this->details->keywords);
			
			$this->reputation = pickFirst(getReputation(array($this->id)));
			
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
			//debug_r($this->details);
			foreach ($this->details as $k => $v){
				if (strpos($k, "user_") === 0){
					$p = $this->getProfile($v);
					if (!empty($p))
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
	}
	
	
	public /*void*/ function delData(){
		
		$publish = new RequestJson($this, array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$_GET['id']), DELETE);
		
		debug('trying to delete '.$this->namespace."..".$_GET['id']);

		$publish->send();
		
		//delete from partners profiles after read
		$this->delete = true;
	
	}
	
	public /*void*/ function addTempPartner(){
		$data = array(
			"tempPartner".$_SESSION['user']->id=>$_SESSION['user']->id
		);
		$publish = new RequestJson( $this, 
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$_GET['id'], "user"=> $_SESSION['user']->id, "user"=>$_SESSION['user']->id, "data"=>$data ),
				UPDATE);
	
		$rs = $publish->send();
		if (empty($this->error))
			$this->success = _("Partnership request sent");

	}
	
	public /*void*/ function addPartner(){

		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id, "field"=>"tempPartner".$_GET["accept"] ),
				DELETE);
		$publish->send();
		
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id, "user"=>"noNotification", "data"=>array("user_".$_GET["accept"]=>$_GET["accept"]) ),
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

		}
		
	}

	
	public /*void*/ function getProfile($id){
		
		$mapper = new DataMapper;
	
		$user = new User($id);
		try {
			$details = $mapper->findById($user);
		} catch (Exception $e) {
			return null;
		}
		$profile = new Profile($details['profile']);
		try {
			$profile->details = $mapper->findById($profile);
		} catch (Exception $e) {
			return null;
		}
		$profile->parseProfile();
		$profile->reputation = pickFirst(getReputation( array($details['profile'])));
		return $profile;
	}
	
	function format($w){
		$w = str_replace(array('"r":','"p":','"c":','"t":','"k":'), array('roles: ','places: ','call: ','themes: ','keywords: '), $w);
		return str_replace(array('{','}','"",'), '', $w);
	}
	
}
?>