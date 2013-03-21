<? 
class CommentController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {

			if(!empty($_POST['wrapped1'])){
				$obj = new Comment();	
				$time = time();
				$obj->publisher = $_SESSION['user']->id;    // Student ID
				$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
				$obj->pred2 = $time;
				$obj->wrapped1 =$_POST['wrapped1'];
				$obj->wrapped2 =$_SESSION['user']->profilePicture;
				
				$obj->publish();
			}
			header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
		}
	}
}
?>