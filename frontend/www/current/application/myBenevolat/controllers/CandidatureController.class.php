<?php 

/**
 * Retrive the list of a user candidatures and render
 * MyCandidatureView 
 */
class CandidatureController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			case 'show_all_candidatures':
				$this->search_all_applies();
				break;
			case 'show_candidatures':
				$this->search_applies();
				break;
		}
	}
	
	private function search_all_applies(){
		$search_applies = new Apply();
		$this->result = $search_applies->find();

		$this->renderView("AllCandidatures");
	}
	
	private function search_applies(){
		$search_applies = new Apply();
		$search_applies->publisher=$_SESSION['user']->id;
		$this->result = $search_applies->find();
	
		$this->renderView("MyCandidature");
	}
}

?>