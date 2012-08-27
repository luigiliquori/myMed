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

			$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "predicates"=>array()));
				
			try{
				$res = $find->send();
			}
			catch(Exception $e){
				//return null;
			}
			$result = $res->results;
			$this->success = "";
			//fetch other infos

			$req = new RequestJson($this, array("application"=>APPLICATION_NAME.":users"));
			
			foreach( $result as $i => $item ){
				$req->addArguments(array("id"=>$item->id));
				try{
					$res = $req->send();
					if (isset($res)){
						$result[$i]->profile = $res->details;
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
	

		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_POST['id'], "data"=>array("permission" => $_POST['perm'], "_notify"=>1)),
				UPDATE);
		
		$publish->send();

	}
	
	public /*void*/ function delProfile(){
	
	
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users","id"=>$_GET['id']),
				DELETE);
		
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