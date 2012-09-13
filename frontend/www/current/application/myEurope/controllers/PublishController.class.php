<? 

class PublishController extends ExtendedProfileRequired {
	
	public $namespace;
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->namespace = $_POST['namespace'];
		debug('namespace ->  '.$this->namespace);
		$index=array();
		
		$themes = array();
		$places = array();

		$t = array_keys(Categories::$themes);
		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $t)){
					array_push($themes, $i);
				} else {
					array_push($places, $i);
				}
			}
		}
		
		array_push($index, new DataBeanv2("themes", ENUM, "|".join("|",$themes)));

		array_push($index, new DataBeanv2("places", ENUM, "|".join("|",$places)));
		
		array_push($index, new DataBeanv2("cats", ENUM, "|".$_POST['cat']));
		
		if ($_POST['call']!="")
			array_push($index, new DataBeanv2("call", ENUM, "|".$_POST['call']));
		
		$p = preg_split('/[ ,+:-]/', $_POST['keywords'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$p = array_filter($p, array($this, "smallWords"));
		$p = array_unique($p);
		array_push($index, new DataBeanv2("keyword", ENUM, "|".join("|",$p)));
		
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
		
		$publish = new RequestJson($this, 
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$id, "user"=>$_SESSION['user']->id, "data"=>$data, "predicates"=>$index, "metadata"=>$metadata),
				CREATE);
		
		$publish->send();
		
		if (!empty($this->error)){
			debug("post err");
			$this->renderView("Main", "post");
		} else {
			
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
			debug_r($index);
			$this->req = "";
			debug("post suc");
			debug(json_encode($_POST));
			unset($_POST['cat']);
			$get_line = "";
			foreach($_POST as $key=>$value){
				$this->req .= '&' . $key . '=' . $value;
			}
			$this->renderView("PublishSuccess");
			//$this->redirectTo("search", $_POST);
			//$this->renderView("Main", "post");
		}
	}
	
	function smallWords($w){
		return strlen($w) > 2;
	}
}
?>