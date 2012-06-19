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
			 * The 2 slots in between are optionnals
			 */
			$callingList = array();
			
			$call1 = array("name" => $_POST["CareGiverName"], "phone" => $_POST["CareGiverPhone"]);
			$call4 = array("name" => "Emergency", "phone" => "112");
			
			// Inserting Caregiver in first position
			array_push($callingList, $call1);
			
			// If user filled the informations for the second calling slot, add it. If not, do nothing.
			if(!empty($_POST["CL_name_1"]) AND !empty($_POST["CL_phone_1"])){
				$call2 = array("name" => $_POST["CL_name_1"], "phone" => $_POST["CL_phone_1"]);
				array_push($callingList, $call2);
			}

			// Same for slot 3
			if(!empty($_POST["CL_name_2"]) AND !empty($_POST["CL_phone_2"])){
				$call3 = array("name" => $_POST["CL_name_2"], "phone" => $_POST["CL_phone_2"]);
				array_push($callingList, $call3);
			}
			
			// Inserting Emergency in last position
			array_push($callingList, $call4);
			
			$extendedProfile = new ExtendedProfile($_SESSION['user'], $diseaseLevel, $careGiver, $doctor, $callingList);
			
			$extendedProfile->storeProfile($this);
			
			
			if (!empty($this->error))
			$this->renderView("ExtendedProfileForm");
			else {
				$this->success = "Complément de profil enregistré avec succès!";
				$this->redirectTo("main");
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