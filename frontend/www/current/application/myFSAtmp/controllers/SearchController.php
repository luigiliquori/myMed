// <?php

// /**
//  *This class extends the default myMed user profile, common to every applications, with a profile specific
//  * for this application.
//  * This extended profile will be stored as a Publication in the database.
//  */

// class ExtendedProfileController extends AbstractController
// {
// 	/**
// 	 * @see IRequestHandler::handleRequest()
// 	 */
// 	public /*void*/ function handleRequest(){
		
// 		/*
// 		 * Determine the dehaviour :
// 		 * POST data ->  Store the profile
// 		 * No Post but ExtendedProfile in session -> show profile
// 		 * Nothing -> show the form to fill the profile
// 		 */
// 		getExtendedProfile();
// 		$this->renderView(search);
		
// 	}
	
// 	public /*void*/ function getPublication(){
	
// 		Publish::getPublication($_SESSION['user']);
// 	}
	

// }