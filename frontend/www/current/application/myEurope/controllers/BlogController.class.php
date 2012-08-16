<? 

//require_once dirname(__FILE__) . '/../../../lib/dasp/beans/DataBeanv2.php';

class BlogController extends AuthenticatedController {
	
	public $blog; // the id of the blog
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->blog = $_REQUEST['blog'];

		if (count($_POST)){
			
			$k="";
			
			if (isset($_POST["rm"])){
				$request = new MatchMakingRequestv2("v2/PublishRequestHandler", DELETE,
						array("id"=>$this->blog, "field"=>$_POST['field']),
						"blogs", $this);
					
				$request->send();
					
			}else{
				
				if (isset($_POST['replyTo']))
					$k = $_POST['replyTo']."^reply^";
	
				$k = $k.time()."^".$_SESSION['user']->id;
				
				$data = array(
					$k => json_encode(array(
							"title"=>isset($_POST['text'])?$_POST['title']:"...",
							"text"=>isset($_POST['text'])?nl2br($_POST['text']):"..."
						))
				);
	
				$publish = new MatchMakingRequestv2("v2/PublishRequestHandler", UPDATE,
						array("id"=>$this->blog, "data"=>json_encode($data)),
					 	"blogs", $this);
	
				$publish->send();
			}
			
		}
		
		$find = new MatchMakingRequestv2("v2/PublishRequestHandler", READ, array("id"=>$this->blog),
				"blogs", $this);
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		if (isset($res)){
			
			$this->messages = array();
			$this->comments = array();
			
			foreach($res as $k => $v){
				$pieces = preg_split("/\^reply\^/", $k);
				if (!isset($this->comments[$pieces[0]]))
					$this->comments[$pieces[0]] = array();
				if(count($pieces)>1)
					$this->comments[$pieces[0]][$pieces[1]] = $v;
				else 
					$this->messages[$pieces[0]] = $v;
					
			}
			
 			foreach ($this->comments as $k=>$v)
 				krsort($this->comments[$k]);
 			krsort($this->messages);
 			debug_r($this->messages);
 			debug_r($this->comments);
			
		} else { //it's empty
			$this->messages = new stdClass();
		}
			
		switch ($this->blog){
			case 'testers':default:
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