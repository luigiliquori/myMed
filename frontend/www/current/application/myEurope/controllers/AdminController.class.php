<? 
class AdminController extends ExtendedProfileRequired {
	
	public $users;
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		if ($_SESSION['myEuropeProfile']->permission > 1){
			
			if (isset($_POST["perm"])){
				$this->updatePermission();
			}
			if (isset($_GET["rm"])){
				$this->delProfile();
			}

			
			$find = new FindRequestv2($this, "users");
				
			try{
				$result = $find->send();
			}
			catch(Exception $e){
				//return null;
			}
			$this->test = $result;
			$this->success = "";
			//fetch other infos
			foreach( $result as $i => $item ){
				$req = new DetailRequestv2($this, "users", $item->id);
				try{
					$res = $req->send();
					if (isset($res, $res->permission))
						$result[$i]->permission = $res->permission;
				} catch(Exception $e){}
				
			}
			// Give this to the view
			$this->users = $result;
			
			$this->renderView("admin");
			
		}
		
	}
	
	public /*void*/ function updatePermission(){
	

		$publish = new PublishRequestv2($this, "users", $_POST['id'], array("permission" => $_POST['perm']));
		$publish->setMethod(UPDATE);
		$publish->send();
	

	}
	
	public /*void*/ function delProfile(){
	
	
		$publish = new PublishRequestv2($this, "users", $_GET['id']);
		$publish->setMethod(DELETE);
		$publish->send();
	
	
	}
	
}
?>