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
		
		$search_publication = new myEuroCINPublication();
		$search_publication->type = "myEuroCIN";
		$res = $search_publication->find();
		$this->result = array();
		
		foreach($res as $item):
			$item->getDetails();			
			if($item->validated=="waiting"){
				array_push($this->result, $item);
			}
		endforeach;
		
		$this->renderView("AllValidations");
	}
	
	/* Accept a publication */
	private function accept() {
		
		$obj = new myEuroCINPublication();
		
		// All required fields are filled, publish it
		$obj = new myEuroCINPublication();
		$obj->type = "myEuroCIN";    					// Type
		$obj->publisher = $_POST['publisher'];    	// Publisher ID
		$obj->publisherID = $_POST['publisher'];
		$obj->Lingua = $_POST['Lingua'];				// locality
		$obj->Nazione = $_POST['Nazione'];				// Language
		$obj->begin = $_POST['begin'];				// begin
		if($_POST['expire_date'] != "--")
			$obj->expire_date = $_POST['expire_date'];	// Expiration date
		$obj->data = $_POST['data'];					// Title
		$obj->text 	= $_POST['text'];					// Publication text
		if( $_POST['Arte_Cultura'] == 'on' ) $obj->Arte_Cultura = "on";
		if( $_POST['Natura'] == 'on' ) $obj->Natura = "on";
		if( $_POST['Tradizioni']  == 'on'  ) $obj->Tradizioni = "on";
		if( $_POST['Enogastronomia'] == 'on'  ) $obj->Enogastronomia = "on";
		if( $_POST['Benessere'] == 'on'  ) $obj->Benessere = "on";
		if( $_POST['Storia'] == 'on' ) $obj->Storia = "on";
		if( $_POST['Religione'] == 'on' ) $obj->Religione = "on";
		if( $_POST['Escursioni_Sport'] == 'on' ) $obj->Escursioni_Sport = "on";
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
	
	/* Refuse a publication */
	private function refuse(){
		
		$this->delete_Comments();
		
		$obj = new myEuroCINPublication();
		$obj->type = "myEuroCIN";    					// Type
		$obj->publisher = $_POST['publisher']; 		   	// Publisher ID
		$obj->publisherID = $_POST['publisher'];  	// Publisher ID
		$obj->Lingua = $_POST['Lingua'];				// locality
		$obj->Nazione = $_POST['Nazione'];				// Language
		$obj->begin = $_POST['begin'];					// Begin
		$obj->Nazione = $_POST['Nazione'];				// Language
		if(isset($_POST['expire_date']) && $_POST['expire_date'] != "--")
			$obj->expire_date = $_POST['expire_date'];	// Expiration date
		$obj->data = $_POST['data'];					// Title
		if( $_POST['Arte_Cultura'] == 'on' ) $obj->Arte_Cultura = "on";
		if( $_POST['Natura'] == 'on' ) $obj->Natura = "on";
		if( $_POST['Tradizioni']  == 'on'  ) $obj->Tradizioni = "on";
		if( $_POST['Enogastronomia'] == 'on'  ) $obj->Enogastronomia = "on";
		if( $_POST['Benessere'] == 'on'  ) $obj->Benessere = "on";
		if( $_POST['Storia'] == 'on' ) $obj->Storia = "on";
		if( $_POST['Religione'] == 'on' ) $obj->Religione = "on";
		if( $_POST['Escursioni_Sport'] == 'on' ) $obj->Escursioni_Sport = "on";

		$obj->delete();
		
		$msgMail = "";
		if(!empty($_POST['msgMail'])) $msgMail = _('<br> Attached message by the admin: "').$_POST['msgMail'].'"';
		
		$mailman = new EmailNotification(substr($_POST['author'],6),_("Your announcement is refused"),_("Your announcement ").$_POST['title']._(" has been refused and therefore removed.").$msgMail);
		$mailman->send();
		
		$this->redirectTo("?action=Validation&method=show_all_validations");
	}
	
	function delete_Comments(){
		$search_by_userid = new Comment();
		$search_by_userid->pred1 = 'comment&'.$_POST['predicate'].'&'.$_POST['author'];
		$result = $search_by_userid->find();
	
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
}

?>