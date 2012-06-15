<?php
//require_once 'models/ExtendedProfile.class.php';

/**
 *	This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application. Store anything you need.
 * Choices have been made that this extended profile will be stored as a Publication in the database.
 * Although, you can change it the way you want in your own application, as long as you use the column families
 * available in cassandra. 
 * 
 * @author David Da Silva
 * 
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
		if (isset($_POST["diseaseLevel"]))
			$this->storeProfile();
		else if (isset($_SESSION['ExtentedProfile']) AND !empty($_SESSION['ExtentedProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileForm");
		
	}
	
	
	public /*void*/ function storeProfile(){

		/*
		 * Retrieve the POST datas
		 */
		//TODO : security checks
		
		if($_POST["agreement"])
		{
			$diseaseLevel = $_POST['diseaseLevel'];
			
			$careGiver = array (
						"name" => $_POST["CareGiverName"],
						"email" => $_POST["CareGiverEmail"],
						"phone" => $_POST["CareGiverPhone"]
						);
			
			$doctor = array(
						"name" => $_POST["DoctorName"],
						"email" => $_POST["DoctorEmail"],
						"phone" => $_POST["DoctorPhone"]
			);
			/*
			 * The First person to call is the caregiver, the last is the emergency services
			 */
			$callingList = array(
			array("name" => $_POST["CareGiverName"], "phone" => $_POST["CareGiverPhone"]),
			array("name" => $_POST["CL_name_1"], "phone" => $_POST["CL_phone_1"]),
			array("name" => $_POST["CL_name_2"], "phone" => $_POST["CL_phone_2"]),
			array("name" => "Emergency", "phone" => "112")
			);
			
			
			$extendedProfile = new ExtendedProfile($_SESSION['user'], $diseaseLevel, $careGiver, $doctor, $callingList);
			
			$extendedProfile->storeProfile($this);
			
			
			if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
			else {
				$this->success = "Complément de profil enregistré avec succès!";
				$this->renderView("main");
			}
			
		}
		else{
			$this->error .= "Agreement needed for the geotagging";
			$this->renderView("ExtendedProfileForm");
		}
	}
	
	
	public /*void*/ function getExtendedProfile(){
		
		ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	
	public /*void*/ function showProfile(){

		$this->renderView("ExtendedProfileDisplay");
	}

}