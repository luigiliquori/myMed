<?php 

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
		debug($obj->publisher);
		$obj->type = "annonce";
		$obj->competences = $_POST['competences'];	// array of competences
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
		
		$this->redirectTo("?action=Validation&method=show_all_validations");
	}
	
	private function refuse(){
		$predicate = $_POST['predicate'];
		$author = $_POST['author'];
		
		$obj = new Annonce($predicate);
		$obj->publisherID = $author;
		$obj->getDetails();
		
		$obj->delete();
		$this->redirectTo("?action=Validation&method=show_all_validations");
	}
}

?>