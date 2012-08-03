<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class PublishController extends AuthenticatedController {
	
	public $namespace;
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->namespace = $_POST['namespace'];
		debug('namespace ->  '.$this->namespace);
		$index=array();
		
		$placesAll = array("france", "italy");
		$themes = array();
		$places = array();

		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( in_array($i, $placesAll)){
					array_push($places, $i);
				} else {
					array_push($themes, $i);
				}
			}
		}
		
		$p = preg_split('/[ +]/', $_POST['themes'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$themes = array_unique(array_merge($themes, $p));
		if (count($themes)){
			array_push($index, new DataBeanv2("themes", ENUM, $themes));
		}
		
		$p = preg_split('/[ +]/', $_POST['places'], NULL, PREG_SPLIT_NO_EMPTY);
		$p = array_map('strtolower', $p);
		$places = array_unique(array_merge($places, $p));
		if (count($places)){
			array_push($index, new DataBeanv2("places", ENUM, $places));
		}

		$data = array(
				"user" => $_SESSION['user']->id,
				"text" => isset($_POST['text'])?$_POST['text']:"contenu vide"
			);
		
		$metadata = array(
				/* @todo more stuff here */
				"user" => $_SESSION['user']->id
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