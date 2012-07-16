<?php

/**
 *This class extends the default myMed user profile, common to every applications, with a profile specific
* for this application.
* This extended profile will be stored as a Publication in the database.
*/

class PublishController extends AbstractController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest(){

		/*
		 * Determine the dehaviour :
		* POST data ->  Store the profile
		* No Post but ExtendedProfile in session -> show profile
		* Nothing -> show the form to fill the profile
		*/
		if (isset($_POST["key1"]))
			$this->storePublication();
		else
			$this->renderView("Publish");

	}


	public /*void*/ function storePublication(){

		/*
		 * Retrieve the POST datas
		*/
		//TODO : security checks
// 		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
// 		$debugtxt  .= var_export($_POST, TRUE);
// 		$debugtxt .= "</pre>";
//         debug($debugtxt);
			
		$key1 = $_POST['key1'];
		$key2 = $_POST['key2'];
		$key3 = $_POST['key3'];
		$publication = $_POST['publication'];
		

			
		$publish = new Publish($_SESSION['user'], $key1, $key2, $key3, $publication);
			
		$publish->storePublication($this);
			
			
		if (!empty($this->error))
			$this->renderView("Publish");
		else {
			$this->success = "Complément de profil enregistré avec succès!";
			$this->redirectTo("main");
		}
			
	}




	// 				$debugtxt 	=	 "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
	// 				$debugtxt 	.=	var_export($key1, TRUE);
	// 				$debugtxt	.=	"</pre>";
	// 				debug($debugtxt);

// 	public /*void*/ function showProfile(){

// 		$this->renderView("ExtendedProfileDisplay");
// 	}

}

?>