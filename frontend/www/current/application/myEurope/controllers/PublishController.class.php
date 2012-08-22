<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

$themesall = array(
		"education",
		"travail",
		"entreprise",
		"environnement",
		"agriculture",
		"peche",
		"recherche",
		"santé"
		);

class PublishController extends AuthenticatedController {
	
	public $namespace;
	
	
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->namespace = $_POST['namespace'];
		debug('namespace ->  '.$this->namespace);
		$index=array();
		
		global $themesall;
		$themes = array();
		$places = array();

		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $themesall)){
					array_push($themes, $i);
				} else {
					array_push($places, $i);
				}
			}
		}
		
		array_push($index, new DataBeanv2("themes", ENUM, $themes));

		array_push($index, new DataBeanv2("places", ENUM, $places));
		
		$p = preg_split('/[ ,+:-]/', $_POST['title'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$p = array_filter($p, array($this, "smallWords"));
		$p = array_unique($p);
		array_push($index, new DataBeanv2("keyword", ENUM, $p));
		
		$t = time();
		
		$data = array(
				"title" => $_POST['title'],
				"user" => $_SESSION['user']->id,
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
		
		$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", CREATE,
				array("id"=>$id, "data"=>json_encode($data), "index"=>json_encode($index), "metadata"=>json_encode($metadata)),
				$this->namespace, $this);
		
		$publish->send();
		
		if (!empty($this->error)){
			debug("post err");
			$this->renderView("Main", "post");
		} else {
			
			// put this project in our profile
			$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", UPDATE,
					array("id"=>$_SESSION['user']->id, "data"=>json_encode(array("part".$id=>$id))),
				 	"users", $this);
			
			$publish->send();
			//push it in session
			array_push($_SESSION['myEuropeProfile']->partnerships, array("part".$id=>$id));
			
			
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