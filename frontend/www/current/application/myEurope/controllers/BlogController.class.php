<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class BlogController extends AuthenticatedController {
	
	public $blog; // the id of the blog
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->blog = $_REQUEST['blog'];
		
		if (isset($_GET["rm"])){
			
			$request = new Requestv2("v2/PublishRequestHandler", DELETE);
			$request->addArgument("application", APPLICATION_NAME);
			$request->addArgument("namespace", "blogs");
			$request->addArgument("id", $this->blog);
			$request->addArgument("field", $_GET['field']);
			$request->send();
		}

		if (count($_POST)){
			
// 			foreach( $_POST as $i=>$v ){
// 				if ($v == "on"){
					
// 				}
// 			}
			
			$k = time()."_".$_SESSION['user']->id;
			
			$data = array(
					$k => isset($_POST['text'])?urlencode($_POST['text']):urlencode("contenu vide")
				);
			
			$publish = new PublishRequestv2($this, "blogs", $this->blog, $data);
			$publish->send();
		}
		
		$find = new DetailRequestv2($this, "blogs", $this->blog, true);
			
		try{
			$this->messages = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		if (isset($this->messages)){
			unset($this->messages->_index);
			debug_r($this->messages);
			$this->messages = (array) $this->messages;
			krsort($this->messages);
		} else { //it's empty
			$this->messages = new stdClass();
		}
			
		switch ($this->blog){
			case 'testers':
				$this->renderView("BlogTesters");
				break;
				
			case 'alcotra':
				$this->renderView("BlogAlcotra");
				break;
		}
		//$this->redirectTo("Main", null, "#blogTest");
	}
}
?>