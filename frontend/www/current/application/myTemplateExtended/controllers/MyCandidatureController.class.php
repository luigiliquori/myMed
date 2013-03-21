<?php 

/**
 * Retrive the list of a user candidatures and render
 * MyCandidatureView 
 */
class MyCandidatureController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			case 'show_candidatures':
				$this->search_apply();
				break;
		}
	}
	
	private function search_apply(){
		$search_applies = new Apply();
		$search_applies->publisher=$_SESSION['user']->id;
		$this->result = $search_applies->find();

		$this->renderView("MyCandidature");
		
	}
}

?>