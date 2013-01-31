<? 
class ApplyController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		switch ($_GET['method']){
			// Show the user publications list
			case 'apply':
				$this->apply();
				break;
			case 'accept':
				$this->accept();
				break;
			case 'refuse':
				$this->refuse();
				break;
		}
	}
	
	private function apply() {
		$obj = new Apply();
		$time = time();
		$obj->publisher = $_SESSION['user']->id;    // Student ID
		$obj->pred1 = 'apply&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
		$obj->pred2 = $time;
		$obj->pred3 = $_SESSION['predicate']; // pred of the publication
		$obj->teacher = $_POST['teacher'];
		$obj->accepted = 'waiting'; // 'accepted' when the teacher accepts the student
		$obj->title = $_POST['title'];
		debug($obj->pred3);
		$obj->publish();
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function accept(){
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		$obj->teacher = $_POST['teacher'];
		$obj->accepted = 'accepted';
		$obj->title = $_POST['title'];
		
		$obj->publish();
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function refuse(){
		debug("REFUSE");
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		$obj->teacher = $_POST['teacher'];
		$obj->accepted = 'accepted';
		$obj->title = $_POST['title'];
	
		$obj->delete();
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
}
?>