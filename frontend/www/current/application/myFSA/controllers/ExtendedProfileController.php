<?php

/**
 *This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application.
 * This extended profile will be stored as a Publication in the database.
 */

class ExtendedProfileController extends AbstractController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest(){
		
		if (isset($_POST["profileFilled"]))
			$this->storeProfile();
		else if (isset($_SESSION['ExtendedProfile']) AND !empty($_SESSION['ExtendedProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileForm");
		
	}	
	
	public /*void*/ function storeProfile(){

		//TODO : security checks
		$_SESSION["profileFilled"] = $_POST["profileFilled"];
		
		if ($_SESSION["profileFilled"] == "company") {
			$object = array(
					"type" => $_POST["ctype"],
					"name" => $_POST["cname"],
					"address" => $_POST["caddress"],
					"number" => $_POST["cnumber"]
			);

		}	
		else if ($_SESSION["profileFilled"] == "employer") {
			$object = array(
					"type" => $_POST["occupation"],
					"name" => $_POST["cname"],
					"address" => $_POST["caddress"],
					"number" => $_POST["tnumber"]
			);
		
		}
		else if ($_SESSION["profileFilled"] == "guest") {
			$object = "guest";
		}
		$extendedProfile = new ExtendedProfile($_SESSION['user'], $object);
		
		$extendedProfile->storeProfile($this);
		if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
		else {
			$this->success = "Registration completed!";
			$this->redirectTo("main");
		}
			
	}
	
	public /*void*/ function getExtendedProfile(){
		
		ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	public /*void*/ function showProfile(){

		$this->renderView("ExtendedProfileDisplay");
	}

}