<?php 

/**
 * Retrive the list of a user publication and render
 * MyPublicationView 
 */
class StudentCoursesController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			// Show the user publications list
			case 'show_student_courses':
				$this->search_apply();
				break;
		}
	}
	
	private function search_apply(){
		$search_applies = new Apply();
		$search_applies->publisher=$_SESSION['user']->id;
		$this->result = $search_applies->find();

		$this->renderView("StudentCourses");
		
	}
}

?>