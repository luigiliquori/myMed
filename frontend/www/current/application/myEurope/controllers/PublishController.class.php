<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class PublishController extends AuthenticatedController {
	
	public $namespace;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		debug('tot '.$_POST['namespace']);
		$this->namespace = $_POST['namespace'];
		debug('wow '.$this->namespace);
		$index=array();
		
		$themes = array();
		$regs = array();
		
		foreach( $_POST as $i=>$v ){
			if ($v == "on"){
				if ( strpos($i, "theme") === 0){
					array_push($themes, substr($i, count("theme")));
				} else if  ( strpos($i, "reg") === 0){
					array_push($regs, substr($i, count("reg")));
				}
			}
		}
		if (count($themes)){
			array_push($index, new DataBeanv2("theme", ENUM, $themes));
		}
		
		if (count($regs)){
			array_push($index, new DataBeanv2("reg", ENUM, $regs));
		}

		$data = array(
					"user" => $_SESSION['user']->id,
					"text" => isset($_POST['text'])?$_POST['text']:"contenu vide"
				);
		
		$publish = new PublishRequestv2($this, $this->namespace, time()."+".$_SESSION['user']->id, $data, $index);
		$publish->send();
		
		if (!empty($this->error)){
			$this->renderView("Main", "post");
		}
			
		else {
			//redirect to search with the indexes
			unset($_POST['text']);	
			$this->redirectTo("search", $_POST);
			//$this->renderView("Main", "post");
		}
	}
}
?>