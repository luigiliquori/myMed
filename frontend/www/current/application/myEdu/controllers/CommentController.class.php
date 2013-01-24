<? 
class CommentController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {
			debug("button comment");
			if(!empty($_POST['wrapped1']) ){
				$obj = new MyEduPublication();
					
				// Fill the object
				$this->fillObj_comments($obj);
				$obj->publish();
			
				$this->result = $obj;
			}
			debug($_SESSION['predicate']." ".$_SESSION['author']);
			header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
		}
	}
	
	private function fillObj_comments($obj) {
		$time = time();
		$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
		$obj->pred2 = $time;
		$obj->wrapped1 =$_POST['wrapped1'];
		$obj->wrapped2 =$_SESSION['user']->profilePicture;
	
	}
}
?>