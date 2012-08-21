<? 
class AdminController extends ExtendedProfileRequired {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		if ($_SESSION['myEuropeProfile']->permission > 1){
			
			if (isset($_POST["perm"])){
				$this->updatePermission();
			}
			if (isset($_GET["rm"])){
				$this->delProfile();
			}

			$find = new MatchMakingRequestv2("v2/FindRequestHandler", READ, null,
					"users", $this);
				
			try{
				$result = $find->send();
			}
			catch(Exception $e){
				//return null;
			}
			$this->test = $result;
			$this->success = "";
			//fetch other infos
			$req = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, null,
				 "users", $this);
			
			foreach( $result as $i => $item ){
				$req->setArguments(array("id"=>$item->id));
				try{
					$res = $req->send();
					if (isset($res)){
						$result[$i]->profile = $res;
					}
						
				} catch(Exception $e){}
				
			}
			// Give this to the view
			$this->users = $result;
			
			$this->blocked = array_filter($result, array($this,"isBlocked"));
			$this->normals = array_filter($result, array($this,"isNormal"));
			$this->admins = array_filter($result, array($this,"isAdmin"));
			
			$this->renderView("admin");
			
		}
		
	}
	
	public /*void*/ function updatePermission(){
	

		$publish =  new MatchMakingRequestv2("v2/PublishRequestHandler", UPDATE,
				array("id"=>$_POST['id'], "data"=>json_encode(array("permission" => $_POST['perm'], "_notify"=>1))),
				"users", $this);
		
		$publish->send();

	}
	
	public /*void*/ function delProfile(){
	
	
		$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", DELETE, array("id"=>$_GET['id']),
				 "users", $this);
		
		$publish->send();
	
	}
	
	public function isBlocked($var){
		return($var->profile->permission <= 0);
	}
	
	public function isNormal($var){
		return($var->profile->permission == 1);
	}
	
	public function isAdmin($var){
		return($var->profile->permission > 1);
	}
	
}
?>