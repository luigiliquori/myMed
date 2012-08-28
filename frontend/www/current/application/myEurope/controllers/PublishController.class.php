<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';



class PublishController extends AuthenticatedController {
	
	public $namespace;
	
	public $themesall = array(
				"education",
				"travail",
				"entreprise",
				"environnement",
				"agriculture",
				"peche",
				"recherche",
				"santé"
			);
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->namespace = $_POST['namespace'];
		debug('namespace ->  '.$this->namespace);
		$index=array();
		
		$themes = array();
		$places = array();

		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $this->themesall)){
					array_push($themes, $i);
				} else {
					array_push($places, $i);
				}
			}
		}
		
		array_push($index, new DataBeanv2("themes", ENUM, "|".join("|",$themes)));

		array_push($index, new DataBeanv2("places", ENUM, "|".join("|",$places)));
		
		$p = preg_split('/[ ,+:-]/', $_POST['title'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$p = array_filter($p, array($this, "smallWords"));
		$p = array_unique($p);
		array_push($index, new DataBeanv2("keyword", ENUM, "|".join("|",$p)));
		
		$t = time();
		
		$data = array(
				"title" => $_POST['title'],
				"time"=>$t,
				"text" => isset($_POST['text'])?$_POST['text']:"contenu vide"
			);
		
		$metadata = array(
				/* @todo more stuff here */
				"title" => $_POST['title'],
				"time"=>$t,
				"user" => $_SESSION['user']->id
			);
		
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
					array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id, "user"=>"noNotification", "data"=>array("part".$id=>$id)),
					UPDATE);
			
			$publish->send();
			//push it in session
			array_push($_SESSION['myEuropeProfile']->partnerships, $id);
			
			$subscribe = new RequestJson( $this,
					array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$id, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":profileMyParts"),
					CREATE, "v2/SubscribeRequestHandler");
			$subscribe->send();
			
			
			//redirect to search with the indexes
			unset($_POST['text']);
			unset($_POST['action']);
			
			$this->req = "";
			debug("post suc");
			debug(json_encode($_POST));
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