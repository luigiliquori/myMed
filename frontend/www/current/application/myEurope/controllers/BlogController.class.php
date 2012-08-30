<? 

class BlogController extends ExtendedProfileRequired {
	
	public $blog; // the id of the blog
	
	public function handleRequest() {
		
		parent::handleRequest();

		$this->blog = $_REQUEST['blog'];

		if (count($_POST)){
			
			if (isset($_POST["rm"])){
				$id = $this->blog;
				if (!empty($_POST["rm"]))
					$id .= "comments".$_POST['rm'];
				
				$request = new RequestJson($this,
						array("application"=>APPLICATION_NAME.":blogs", "id"=>$id, "field"=>$_POST['field']),
						DELETE);
				$request->send();
					
			}else{
				
				$id = $this->blog;
				$t = time();
				$k = hash("crc32", $t.$_SESSION['user']->id);
				
				if (isset($_POST['commentTo'])){ // a comment
					
					$id .= "comments".$_POST['commentTo'];
					$data = array(
							$k => json_encode(array(
									"time"=>$t,
									"user"=>$_SESSION['user']->id,
									"replyTo"=>$_POST['replyTo'],
									"text"=>nl2br($_POST['text'])
							))
					);
					
				} else { // a blog message
					$data = array(
							$k => json_encode(array(
									"time"=>$t,
									"user"=>$_SESSION['user']->id,
									"title"=>$_POST['title'],
									"text"=>$_POST['text']
							))
					);
				}

				$publish = new RequestJson($this,
						array("application"=>APPLICATION_NAME.":blogs", "id"=>$id, "data"=>$data),
						UPDATE);
				$publish->send();
			}
			
		}
		
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":blogs", "id"=>$this->blog));
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		if (isset($res->details)){
			
			$this->messages = array();
			foreach ($res->details as $k=>$v){
				$this->messages[$k] = json_decode($v, true);
			}
			uasort($this->messages, array($this, "timeCmp"));
			
			

			$rep =  new Reputationv2(array_keys($this->messages));
			$repArr = $rep->send();
			
			$this->messages = array_replace_recursive($this->messages, $repArr);
			
			$this->comments = array();
			
			$req = new RequestJson($this, array("application"=>APPLICATION_NAME.":blogs"));
			
			foreach($res->details as $k => $v){
				
				$req->addArguments(array("id"=>$this->blog."comments".$k));
				try{
					$r = $req->send();
				} catch(Exception $e){}
				
				$this->comments[$k] = array();
				if (isset($r->details)){
					
					foreach ($r->details as $ki=>$vi){
						$this->comments[$k][$ki] = json_decode($vi, true);
					}
					$rep =  new Reputationv2(array_keys($this->comments[$k]));
					$repArr = $rep->send();
					$this->comments[$k] = array_replace_recursive($repArr, $this->comments[$k]);
					
					uasort($this->comments[$k], array($this, "repCmp"));
					
				}
			}
			
		} else { //it's empty
			$this->messages = array();
		}
			
		switch ($this->blog){
			case 'tests':default:
				$this->renderView("Blogs");
				break;
				
		}
	}
	
	public function timeCmp($a, $b){
		return  $a['time'] < $b['time'];
	}
	
	public function repCmp($a, $b){
		if ($a['up']-$a['down'] == $b['up']-$b['down'])
			return $this->timeCmp($a, $b);
		return  $a['up']-$a['down'] < $b['up']-$b['down'];
	}
	
}
?>