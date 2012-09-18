<? 

class PublishController extends ExtendedProfileRequired {
	
	public $namespace;
	
	public function handleRequest() {
		
		parent::handleRequest();

		$t = time();
		
		$data = array(
				"title" => $_POST['title'],
				"time"=>$t,
				"user" => $_SESSION['user']->id,
				"partner" => $_SESSION['myEurope']->profile,
				"text" => !empty($_POST['text'])?$_POST['text']:"contenu vide"
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
		
		$this->part = new Partnership($this, $id, $data, $metadata);
		$this->part->initCreate($_POST);
		
		try{
			$this->result = $this->part->create();
		}catch(Exception $e){
			debug("post err".$e);
			$this->setError($e);
			$this->renderView("Main", "post");
		}
		
		
		
		// put this project in our profile
		$publish = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_SESSION['myEurope']->profile, "user"=>"noNotification", "data"=>array("part_".$id=>$id)),
				UPDATE);
		
		$publish->send();
		//push it in session
		array_push($_SESSION['myEuropeProfile']->partnerships, $id);
		
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$id, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":profileParts"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		
		
		//redirect to search with the indexes
		unset($_POST['text']);
		unset($_POST['action']);
		$this->req = "";
		debug("post suc");
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