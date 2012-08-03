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
class ExtendedProfileController extends AuthenticatedController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Determine the dehaviour :
		 * POST data ->  Store the profile
		 * No Post but ExtendedProfile in session -> show profile
		 * Nothing -> show the form to fill the profile
		 */

		if (isset($_POST["form"]))
			$this->storeProfile();
		else if (isset($_GET['edit']) OR isset($_POST['sudo']))
			$this->editProfile();
		else if (isset($_SESSION['ExtendedProfile']) AND !empty($_SESSION['ExtendedProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileCreate");
		
	}
	
	
	public /*void*/ function storeProfile(){

		/*
		 * Retrieve the POST datas
		 */
		//TODO : security checks
		
		if($_POST["agreement"])
		{
			$home = $_POST['home'];
			$diseaseLevel = $_POST['diseaseLevel'];
			
			$careGiver = array (
						"name" => $_POST["CareGiverName"],
						"address" => $_POST["CareGiverAddress"],
						"email" => $_POST["CareGiverEmail"],
						"phone" => $_POST["CareGiverPhone"]
						);
			
			$doctor = array(
						"name" => $_POST["DoctorName"],
						"address" => $_POST["DoctorAddress"],
						"email" => $_POST["DoctorEmail"],
						"phone" => $_POST["DoctorPhone"]
			);
			/*
			 * The First person to call is the caregiver, the last is the emergency services
			 * The 2 slots in between are optionnals
			 */
			$callingList = array();
			
			$call1 = array("name" => $_POST["CareGiverName"], "address" => $_POST["CareGiverAddress"], "email" => $_POST["CareGiverEmail"], "phone" => $_POST["CareGiverPhone"]);
			//$call4 = array("name" => "Emergency", "address" => "" , "email" => "", "phone" => "112");
			//TODO uncomment emergency
			$call4 = array("name" => "Emergency", "address" => "-" , "email" => "-", "phone" => "-");
			// Inserting Caregiver in first position
			array_push($callingList, $call1);
			
			// If user filled the informations for the second calling slot, add it. If not, do nothing.
			if(!empty($_POST["CL_name_1"]) AND !empty($_POST["CL_email_1"]) AND !empty($_POST["CL_phone_1"])){
				$call2 = array("name" => $_POST["CL_name_1"], "address" => $_POST["CL_address_1"], "email" => $_POST["CL_email_1"], "phone" => $_POST["CL_phone_1"]);
				array_push($callingList, $call2);
			}

			// Same for slot 3
			if(!empty($_POST["CL_name_2"]) AND !empty($_POST["CL_email_1"]) AND !empty($_POST["CL_phone_2"])){
				$call3 = array("name" => $_POST["CL_name_2"], "address" => $_POST["CL_address_2"], "email" => $_POST["CL_email_2"], "phone" => $_POST["CL_phone_2"]);
				array_push($callingList, $call3);
			}
			
			// Inserting Emergency in last position
			array_push($callingList, $call4);

			$extendedProfile = new ExtendedProfile($_SESSION['user'], $home, $diseaseLevel, $careGiver, $doctor, $callingList);
			
			$extendedProfile->storeProfile($this);
			
			
			if (!empty($this->error)){
				if ($_POST['form'] == 'create')
					$this->renderView("ExtendedProfileCreate");
				else
					$this->renderView("ExtendedProfileEdit");
			}
				
			else {
				
				/*
				 * If it was an edit, reload the ExtendedProfile in the $_SESSION
				 */
				if ($_POST['form'] == 'edit'){
					$_SESSION['ExtendedProfile'] = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
					$this->success = "Complément de profil modifié avec succès!";
					$this->renderview("ExtendedProfileDisplay");
				}
					
				$this->success = "Complément de profil enregistré avec succès!";
				$this->renderView("main");
			}
			
		}
		else{
			$this->error .= "Agreement needed for the geotagging";
			if ($_POST['form'] == 'create')
				$this->renderView("ExtendedProfileCreate");
			else
				$this->renderView("ExtendedProfileEdit");
		}
	}
	
	/**
	 * Edit the extended profile.
	 * In order to édit it, the user is asked to enter his password, like a SUDO permission.
	 * This prevent the user from editing critical informations
	 */
	public /*void*/ function editProfile(){
		
		/*
		 * If the password iis valid, the accessToken should be in the url
		 */
		if($_GET['edit'] == 'true' )
		{
			/*
			 * Checking the password
			 */
			if( isset($_POST['sudo'])){ // edit = true AND sudo posted
				
				$pass	= hash("sha512", $_POST['password']);
				
				// Building the Authentication request
				$request = new Request("AuthenticationRequestHandler", READ);
				$request->addArgument("login", $_SESSION['user']->login);
				$request->addArgument("password", $pass);
				
				// Sending request
				$responsejSon = $request->send();
					
				$responseObject = json_decode($responsejSon);
				
				// In case of errors
				if($responseObject->status != 200) {
				
					// Save the error
					$this->error = "Error, wrong password";
					$this->renderView("ExtendedProfileDisplay");
				
				} else { // edit = true AND sudo posted AND password valid
				
					// Everything went fine, we have now an accessToken in the session
					$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
					$get_url = array(
								"edit" => "true"
					);
					
					// redirect to the editForm of ExtendedProfile
					$this->redirectTo("ExtendedProfile", $get_url);
				}
				
			}// /isset sudo
			else { // edit = true AND no sudo
				$this->renderView("ExtendedProfileEdit");
			}
			
			
		} // if edit = false
		else{
			$this->renderView("ExtendedProfileSudoDialog");
		}
		
	}
	
	
	public /*void*/ function showProfile(){

		$this->renderView("ExtendedProfileDisplay");
	}

}