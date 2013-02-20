<? 

include("models/EmailNotification.class.php");

/**
 * Implements the apply publication mechanism 
 */
class ApplyController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		switch ($_GET['method']){
			
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
	
	/** Apply Request from the user to the author */
	private function apply() {
		$obj = new Apply();
		$time = time();
		$obj->publisher = $_SESSION['user']->id;    // applier's ID
		$obj->pred1 = 'apply&'.$_POST['predicate'].'&'.$_POST['author'];
		$obj->pred2 = $time;
		$obj->idAnnonce = $_POST['id']; // id of the publication
		$obj->author = $_POST['author'];
		$obj->accepted = 'waiting'; // 'accepted' when the author accepts the student
		$obj->title = $_POST['title'];
		
		$obj->publish();
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$admins;
		foreach($admins as $a){
			$mailman = new EmailNotification(substr($a->email,6),_("Someone apply to an announcement"),_("Someone apply to the announcement ").$_POST['title']._(" please check on the web interface."));
			$mailman->send();
		}
		*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->success = _("Your request has been sent. You must wait for its validation.");
		$this->redirectTo("?action=Candidature&method=show_candidatures");
	}
	
	
	/** 
	 * Accept an applier to a course 
	 */
	function accept() {
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->idAnnonce = $_POST['id'];
		$obj->author = $_POST['author'];
		$obj->accepted = 'accepted';
		$obj->title = $_POST['title'];
		
		$obj->publish();
		
		$msgMail = "";
		if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the author: "').$_POST['msgMail'].'"';
		
		$mailman = new EmailNotification(substr($_POST['publisher'],6),_("Your application is accepted"),_("Your application to ").$_POST['title']._(" has been accepted").$msgMail);
		$mailman->send();
		
		$mailman2 = new EmailNotification(substr($_POST['author'],6),_("You have a new volunteer"),_("A volunteer has been accepted for your announcement ").$_POST['title']);
		$mailman2->send();
		
		header("location: index.php?action=Candidature&method=show_all_candidatures");
	}
	
	
	/**
	 * Refuse an applier to a course
	 */
	function refuse() {
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->idAnnonce = $_POST['id'];
		$obj->author = $_POST['author'];
		$obj->accepted = $_POST['accepted'];
		$obj->title = $_POST['title'];
		$obj->delete();
		
		$msgMail = "";
		if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the author: "').$_POST['msgMail'].'"';
		
		$mailman = new EmailNotification(substr($_POST['publisher'],6),_("Your apply is refused"),_("Your apply to ").$_POST['title']._(" has been refused.").$msgMail);
		$mailman->send();
		
		header("location: index.php?action=Candidature&method=show_all_candidatures");
	}
}
?>