<? 

class PublishController extends ExtendedProfileRequired {
	
	function create() {

		$t = time();
		$data = array(
				"title" => $_POST['title'],
				"time"=>$t,
				"user" => $_SESSION['user']->id,
				"partner" => $_SESSION['myEurope']->profile,
				"text" => !empty($_POST['text'])?$_POST['text']:"contenu vide",
			);
		
		$metadata = array(
				/* @todo more stuff here */
				"title" => $_POST['title'],
				"time"=>$t,
				"user" => $_SESSION['user']->id,
				"partner" => $_SESSION['myEurope']->profile,
			);
		
		if (!empty($_POST['date'])){
			$data['expirationDate'] = $_POST['date'];
			$metadata['expirationDate'] = $_POST['date'];
		}
		
		$id = hash("md5", $t.$_SESSION['user']->id);
		
		$this->part = new Partnership($id, $data, $metadata);
		$this->part->setUser($_SESSION['user']->id);
		$this->part->setBroadcastIndex($_POST);
		
		$mapper = new DataMapper;
		
		try{
			$res = $mapper->save($this->part);
		}catch(Exception $e){
			$this->setError($e);
			$this->redirectTo("Main", null, "post");
		}
		
		// put this project in our profile
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_SESSION['myEurope']->profile, "user"=>"noNotification", "data"=>array("part_".$id=>$id)),
				UPDATE);
		
		$publish->send();
		//push it in session
		array_push($_SESSION['myEurope']->partnerships, $id);
		
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":part", "id"=>$id, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":profileParts"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		
		
		//redirect to search with the indexes
		unset($_POST['text']);
		unset($_POST['action']);
		unset($_POST['method']);
		$this->req = "";

		debug(json_encode($_POST));
		unset($_POST['r']);
		$get_line = "";
		$this->req = http_build_query($_POST);
		
		$this->renderView("PublishSuccess");
		//$this->redirectTo("search", $_POST);
		//$this->renderView("Main", "post");
		
	}

}
?>