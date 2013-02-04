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
	
	/* Apply Request from the user to the author */
	private function apply() {
		$nbMaxAppliers = $_POST['maxappliers'];
		$nbCurrentAppliers = $_POST['currentappliers'];
		$canApply=true;
		if($_POST['category']=='Course'){
			if($nbCurrentAppliers+1>$nbMaxAppliers){
				$canApply=false;
				$this->error("You can't apply, there is no more place!");
			}
		}
		if($canApply==true){
			$obj = new Apply();
			$time = time();
			$obj->publisher = $_SESSION['user']->id;    // Student ID
			$obj->pred1 = 'apply&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
			$obj->pred2 = $time;
			$obj->pred3 = $_SESSION['predicate']; // pred of the publication
			$obj->author = $_POST['author'];
			$obj->accepted = 'waiting'; // 'accepted' when the author accepts the student
			$obj->title = $_POST['title'];
			$obj->publish();
			
			$mailman = new EmailNotification(strtr($_POST['author'],"MYMED_", ""),_("Someone apply to your publication"),_("Someone apply to your publication ").$_POST['title']._(" please check on the web interface"));
			$mailman->send();
			$mailman2 = new EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your application is awaiting validation"),_("Your application to ").$_POST['title']._("is awaiting validation"));
			$mailman2->send();
		}
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function accept(){
		$nbMaxAppliers = $_POST['maxappliers'];
		$nbCurrentAppliers = $_POST['currentappliers'];
		$canApply=true;
		if($_POST['category']=='Course'){
			if($nbCurrentAppliers+1<=$nbMaxAppliers){
				if($nbCurrentAppliers==-1){ // the default value is not 0 but -1
					$this->update_nb_Appliers(1);
				}else{
					$this->update_nb_Appliers($nbCurrentAppliers+1);
				}
			}else{
				$canApply=false;
				$this->error("You can't accept, there is no more place!");
			}
		}
		if($canApply==true){
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
			
			$mailman = new EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your application is accepted"),_("Your application to ").$_POST['title']._(" has been accepted"));
			$mailman->send();
		}
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function refuse(){
		$nbMaxAppliers = $_POST['maxappliers'];
		$nbCurrentAppliers = $_POST['currentappliers'];
		$canApply=true;
		if($_POST['category']=='Course'){
			if($_POST['accepted']=='accepted'){ // was accepted 
				if($nbCurrentAppliers-1==0){ // the default value is not 0 but -1 
					$this->update_nb_Appliers(-1);
				}else{
					$this->update_nb_Appliers($nbCurrentAppliers-1);
				}
			}
		}
		$obj = new Apply();
		$obj->type = 'apply';
		$obj->publisherID = $_POST['publisher'];
		$obj->publisher = $_POST['publisher'];
		$obj->pred1=$_POST['pred1'];
		$obj->pred2 = $_POST['pred2'];
		$obj->pred3 = $_POST['pred3'];
		$obj->author = $_POST['author'];
		$obj->accepted = $_POST['accepted'];
		$obj->title = $_POST['title'];
		$obj->delete();
		
		$mailman = new EmailNotification(strtr($_POST['publisher'],"MYMED_", ""),_("Your apply is refused"),_("Your apply to ").$_POST['title']._("has been refused"));
		$mailman->send();
		
		header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
	}
	
	function update_nb_Appliers($newCurrentAppliers){
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
}
?>