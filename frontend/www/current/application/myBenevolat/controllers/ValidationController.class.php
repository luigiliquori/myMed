<?php 

include("models/EmailNotification.class.php");

/**
 * Retrive the list of a user candidatures and render
 * MyCandidatureView 
 */
class ValidationController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			case 'show_all_validations':
				$this->search_all_validations();
				break;
			case 'accept':
				$this->accept();
				break;
			case 'refuse':
				$this->refuse();
				break;
		}
	}
	
	private function search_all_validations(){
		$search_applies = new Annonce();
		$res = $search_applies->find();
		$this->result = array();
		
		foreach($res as $item):
			$item->getDetails();			
			if($item->validated=="waiting"){
				array_push($this->result, $item);
			}
		endforeach;
		
		$this->renderView("AllValidations");
	}
	
	private function accept(){
		$obj = new Annonce();
		
		$obj->publisher = $_POST['publisher'];
		$obj->publisherID = $_POST['publisher'];
		$obj->type = "annonce";
		$obj->id = $_POST['id'];
		$competences = explode(ENUM_SEPARATOR, $_POST['competences']);
		if (sizeof($competences) == 1) $competences = $competences[0];
		$obj->competences = $competences;
		$obj->typeMission = $_POST['mission'];
		$obj->quartier = $_POST['quartier'];
		$obj->begin = $_POST['begin'];
		$obj->end 	= $_POST['date'];
		$obj->title = $_POST['title'];
		$obj->text = $_POST['text'];
		$obj->promue = $_POST['promue'];
		$obj->validated = "validated";
		
		// sets the level of broadcasting in the Index Table
		$level = 3;
		$obj->publish($level);
		
		$msgMail = "";
		if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the admin: "').$_POST['msgMail'].'"';
		
		$mailman = new EmailNotification(substr($_POST['publisher'],6),_("Your announcement is validated"),_("Your announcement ").$_POST['title']._(" has been validated.").$msgMail);
		$mailman->send();
		
		$this->redirectTo("?action=Validation&method=show_all_validations");
	}
	
	private function refuse(){
		$this->delete_Applies();
		
		$request = new Annonce();
		$request->id = $_POST['id'];
		$res = $request->find();

		$obj = $res[0];
		$obj->getDetails();
		
		$obj->delete();
		
		$msgMail = "";
		if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the admin: "').$_POST['msgMail'].'"';
		
		$mailman = new EmailNotification(substr($_POST['author'],6),_("Your announcement is refused"),_("Your announcement ").$_POST['title']._(" has been refused and therefore removed.").$msgMail);
		$mailman->send();
		
		$this->redirectTo("?action=Validation&method=show_all_validations");
	}
	
	function delete_Applies(){
		$search_by_userid = new Apply();
		$search_by_userid->pred1 = 'apply&'.$_POST['id'].'&'.$_POST['author'];
		$result = $search_by_userid->find();
	
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
}

?>