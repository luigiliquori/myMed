<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class PublishController extends AuthenticatedController {
	
	public $namespace;
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->namespace = $_POST['namespace'];
		debug('namespace ->  '.$this->namespace);
		$index=array();
		
		$themes = array();
		$regs = array();
		
		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( strpos($i, "theme") === 0){
					array_push($themes, substr($i, strlen("theme")));
				} else if  ( strpos($i, "reg") === 0){
					array_push($regs, substr($i, strlen("reg")));
				}
			}
		}
		if (count($themes)){
			array_push($index, new DataBeanv2("theme", ENUM, $themes));
		}
		
		if (count($regs)){
			array_push($index, new DataBeanv2("reg", ENUM, $regs));
		}
		
		$tags = preg_split('/[ +]/', $_POST['q'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_unique(array_map('strtolower', $tags));
		if (count($p)){
			array_push($index, new DataBeanv2("tags", ENUM, $p));
		}

		$data = array(
					"user" => $_SESSION['user']->id,
					"text" => isset($_POST['text'])?$_POST['text']:"contenu vide"
				);
		
		$metadata = array(
				/* @todo more stuff here */
				"user" => $_SESSION['user']->id,
			);
		
		$publish = new PublishRequestv2($this, $this->namespace, time()."+".$_SESSION['user']->id, $data, $index, $metadata);
		$publish->send();
		
		if (!empty($this->error)){
			debug("post err");
			$this->renderView("Main", "post");
		} else {
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
}
?>