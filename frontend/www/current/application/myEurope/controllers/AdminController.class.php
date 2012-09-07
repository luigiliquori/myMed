<? 
class AdminController extends ExtendedProfileRequired {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		if ($_SESSION['myEurope']->permission > 1){
			
			if (isset($_GET["perm"])){
				$this->updatePermission();
			}
			
			if (isset($_POST["perm"])){
				$this->updatePermission();
			}
			if (isset($_GET["rm"])){
				$this->redirectTo("extendedProfile", array("rmUser"=>$_GET['rm']));
			}

			$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "predicates"=>array()));
				
			try{
				$res = $find->send();
			}
			catch(Exception $e){}
			if (isset($res)){
				$this->success = "";
				
				$this->users = $res->results;
					debug_r($this->users);
				$this->blocked = array_filter($this->users, array($this,"isBlocked"));
				$this->normals = array_filter($this->users, array($this,"isNormal"));
				$this->admins = array_filter($this->users, array($this,"isAdmin"));
					
				$this->renderView("admin");
			}
			$this->renderView("main");
			
		}
		
	}
	
	public /*void*/ function updatePermission(){
	
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_GET['id'], "data"=>array("permission" => $_GET['perm']), "metadata"=>array("permission" => $_GET['perm'])),
				UPDATE);
		
		$publish->send();
		$this->redirectTo("Admin");

	}
	
	public function isBlocked($var){
		return($var->permission <= 0);
	}
	
	public function isNormal($var){
		return($var->permission == 1);
	}
	
	public function isAdmin($var){
		return($var->permission > 1);
	}
	
}
?>