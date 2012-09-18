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
			
			$this->success = "";
			
			$this->users = $res->results;
			debug_r($this->users);
			
			$this->blocked = array();
			$this->normals = array();
			$this->admins = array();
			array_walk($this->users, array($this,"userFilter"));
				
			$this->renderView("admin");
			
		}
		
	}
	
	public /*void*/ function updatePermission(){
	
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_GET['id'], "data"=>array("permission" => $_GET['perm']), "metadata"=>array("permission" => $_GET['perm'])),
				UPDATE);
		
		$publish->send();
		$this->redirectTo("Admin");

	}
	
	public function userFilter($var){
		//$var->id = filter_var($var->id, FILTER_SANITIZE_EMAIL);
		if (isset($var->id)){
			if ($var->permission < 1){
				$this->blocked[] = $var;
			} else if ($var->permission == 1){
				$this->normals[] = $var;
			} else {
				$this->admins[] = $var;
			}
		}
	}
}
?>