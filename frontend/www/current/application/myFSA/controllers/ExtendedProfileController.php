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
		
		/*
		 * Determine the dehaviour :
		 * POST data ->  Store the profile
		 * No Post but ExtendedProfile in session -> show profile
		 * Nothing -> show the form to fill the profile
		 */
		if (isset($_POST["companyName"]))
			$this->storeProfile();
		else if (isset($_SESSION['ExtendedProfile']) AND !empty($_SESSION['ExtendedProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileForm");
		
	}
	
	
	public /*void*/ function storeProfile(){

		/*
		 * Retrieve the POST datas
		 */
		//TODO : security checks
			
			$companyName = $_POST['companyName'];
			
			$doctor = array(
						"name" => $_POST["DoctorName"],
						"email" => $_POST["DoctorEmail"],
						"phone" => $_POST["DoctorPhone"]
			);
			
			$extendedProfile = new ExtendedProfile($_SESSION['user'], $companyName, $doctor);
			
			$extendedProfile->storeProfile($this);
			
			
			if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
			else {
				$this->success = "Complément de profil enregistré avec succès!";
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