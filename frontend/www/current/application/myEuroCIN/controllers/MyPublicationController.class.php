<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class MyPublicationController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		// Retrieve all the user's projects
		$search = new Partnership();
		// TODO FIX : use fake user for now
		// $search->publisher = $_SESSION['user']->id;
		$search->publisher = "FAKE_USER_ID";
		// END TODO FIX : use fake user for now
		$this->result = $search->find();
		
		// Render MyPublicationView
		$this->renderView("MyPublication");
		
	}
	
}

?>