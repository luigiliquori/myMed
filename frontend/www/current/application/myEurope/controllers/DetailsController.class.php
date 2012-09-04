<? 
require_once 'profile-utils.php';

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
		
		$this->text = "description vide";

		$req = new RequestJson( $this, array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id));
		
		try{
			$res = $req->send();
		}
		catch(Exception $e){
			$this->details=new StdClass();
			$this->details->text = "Contenu effacé par l'auteur";
		}

		if (isset($res->details)){
			$this->details = $res->details;
			$rep =  new Reputationv2($this, $this->details->user, $this->id);
			$this->reputation = $rep->send();
			
			if (isset($this->details->user)){
				$this->details->userProfile = getProfilefromUser($this, $this->details->user);
				
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
					$p = getProfilefromUser($this, $v);
					array_push($this->partnersProfiles, $p);
				}
			}
			
			if ($this->delete){
				$request = new RequestJson($this,
						array("application"=>APPLICATION_NAME.":profiles", "id"=>"", "field"=>"part_".$this->id),
						DELETE);
				
				foreach ($this->partnersProfiles as $v){
					$req->addArguments(array("id"=>$v->id));
					$request->send();
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
				array("application"=>APPLICATION_NAME.":".$_GET['namespace'],"id"=>$this->id, "user"=>"noNotification", "data"=>array("user_".$_GET["accept"]=>$_GET["accept"]) ),
				UPDATE);
		$publish->send();
		
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_GET["accept"], "data"=>array("part_".$this->id=>$this->id) ),
				UPDATE);
		$publish->send();
		
		if (empty($this->error))
			$this->success = _("Partnerships added");
	}
	
	
}
?>