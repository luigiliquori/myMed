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
		$obj->author = $_POST['author'];
		$obj->accepted = 'waiting'; // 'accepted' when the author accepts the student
		$obj->title = $_POST['title'];
		debug($obj->pred3);
		$obj->publish();
		$mailman = EmailNotification(strtr($_POST['author'],"MYMED_", ""),_("Someone apply to your publication"),_("Someone apply to your publication ").$_POST['title']._(" please check on the web interface"));
		$mailman->send();
		$mailman2 = EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your application is awaiting validation"),_("Your application to ").$_POST['title']._("is awaiting validation"));
		$mailman2->send();
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function changeNbAppliers($newCurrentAppliers){
		$obj = new MyEduPublication();
		$obj->publisher = $_POST['author'];
		$obj->area = $_POST['area'];				
		$obj->category = $_POST['category'];		
		$obj->locality = $_POST['locality'];		
		$obj->organization = $_POST['organization'];
		$obj->end 	= $_POST['date'];				
		$obj->title = $_POST['title'];				
		$obj->text 	= $_POST['text'];
		$obj->maxappliers 	= $_POST['maxappliers'];
		$obj->currentappliers = $newCurrentAppliers;
		$obj->publish();
	}
	
	function accept(){
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		$obj->author = $_POST['author'];
		$obj->accepted = 'accepted';
		$obj->title = $_POST['title'];
		
		$obj->publish();
		$mailman = EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your application is accepted"),_("Your application to ").$_POST['title']._(" has been accepted"));
		$mailman->send();
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
		$obj->author = $_POST['author'];
		$obj->accepted = 'accepted';
		$obj->title = $_POST['title'];
		$mailman = EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your apply is refused"),_("Your apply to ").$_POST['title']._("has been refused"));
		$mailman->send();
		$obj->delete();
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
}
?>