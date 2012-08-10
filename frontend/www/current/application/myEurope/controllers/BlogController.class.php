<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class BlogController extends AuthenticatedController {
	
	public $blog; // the id of the blog
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->blog = $_REQUEST['blog'];
		
		if (isset($_GET["rm"])){
			
			$request = new MatchMakingRequestv2("v2/PublishRequestHandler", DELETE,
					array("id"=>$this->blog, "field"=>$_GET['field']),
					"blogs", $this);
			
			$request->send();
		}

		if (count($_POST)){
			
// 			foreach( $_POST as $i=>$v ){
// 				if ($v == "on"){
					
// 				}
// 			}
			
			$k = time()."^".$_SESSION['user']->id;
			
			debug($k);
			
			$data = array(
					$k => isset($_POST['text'])?urlencode(nl2br($_POST['text'])):urlencode("...")
				);
			
			$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", CREATE,
					array("id"=>$this->blog, "data"=>json_encode($data)),
				 	"blogs", $this);

			$publish->send();
		}
		
		$find = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, array("id"=>$this->blog),
				"blogs", $this);
			
		try{
			$this->messages = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		if (isset($this->messages)){
			unset($this->messages->_index);
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