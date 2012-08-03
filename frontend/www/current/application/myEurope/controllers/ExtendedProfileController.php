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
	
	/**
	 * id of the profile
	 */
	public $id;
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		/*
		 * Determine the dehaviour :
		 * POST data ->  Store the profile
		 * No Post but ExtendedProfile in session -> show profile
		 * Nothing -> show the form to fill the profile
		 */

		if (isset($_POST["form"])){
			$this->storeProfile();
		}
			
		else if (isset($_GET['edit']))
			$this->editProfile();
		else if (isset($_GET['id'])){
			$this->showOtherProfile($_GET['id']);
		}
		else if (isset($_SESSION['myEuropeProfile']))
			$this->showProfile();
		else
			$this->renderView("ExtendedProfileCreate");
		
	}
	
	
	public /*void*/ function storeProfile(){

		/*
		 * Retrieve the POST datas
		 */
		//TODO : security checks

		
		//$extendedProfile = new ExtendedProfile($_SESSION['user'], $home, $diseaseLevel, $careGiver, $doctor, $callingList);
		
		//$extendedProfile->storeProfile($this);
		
		
		
		if ($_POST['form']=="create"){
			$permission = (
					(strpos($_SESSION['user']->email, "@inria.fr") !== false)
					|| $_SESSION['user']->email=="bredasarah@gmail.com"
					|| $_SESSION['user']->email=="myalpmed@gmail.com"
			)? 2 : 0;
			$_POST['permission'] = $permission;
			
		} else { //check password
			$_POST['permission'] = $_SESSION['myEuropeProfile']->permission;//let's not lose the permission
			$pass	= hash("sha512", $_POST['password']);
			if( empty($pass) ){
				// TODO i18n
				$this->error = "Password cannot be empty!";
				$this->renderView("ExtendedProfileEdit");
			}
			$request = new Requestv2("v2/AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_SESSION['user']->login);
			$request->addArgument("password", $pass);	
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
				debug("error");	
				$this->renderView("ExtendedProfileEdit");
			}
		}

		debug($_SESSION['user']->email);
		
		
		$_POST['user'] = $_SESSION['user']->id;
		
		// we clear these ones
		$edit = ($_POST['form'] == "edit");
		unset($_POST['form']);
		unset($_POST['checkCondition']);

		// and publish $_POST
		$publish = new PublishRequestv2($this, "users", $_SESSION['user']->id, $_POST);
		$publish->setMethod(UPDATE);
		$publish->send();
		
		if (!empty($this->error)){
			if ($_POST['form'] == 'create')
				$this->renderView("ExtendedProfileCreate");
			else
				$this->renderView("ExtendedProfileEdit");
		}
			
		else {
			
			$_SESSION['myEuropeProfile'] = (object) $_POST;
			
			$this->success = "Complément de profil enregistré avec succès!";
			
			//..
			
			/*
			 * If it was an edit, reload the ExtendedProfile in the $_SESSION
			 */
			
			if ($edit){
				$this->redirectTo("Main", null, "#profile");
			} else {
				$_SESSION['myEuropeProfile']->permission = $permission;
				if ($permission <= 0)
					$this->renderView("WaitingForAccept");
				else
					$this->renderView("main");
			}
			
		}
	
	}
	
	/**
	 * Edit the extended profile.
	 * In order to édit it, the user is asked to enter his password, like a SUDO permission.
	 * This prevent the user from editing critical informations
	 */
	public /*void*/ function editProfile(){
		
		$this->renderView("ExtendedProfileEdit");
		
	}
	
	public /*void*/ function showOtherProfile($id){
	
		$find = new DetailRequestv2($this, "users", $id);
			
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		
		if (!empty($result)){
		
			$this->profile = (object) $result;
			$this->success = "";
			
			$rep =  new Reputationv2($id);
			$this->reputation = $rep->send();

		}
		
		$this->renderView("ExtendedProfileDisplay");
		
	}
	
	public /*void*/ function showProfile(){

		$this->redirectTo("Main", null, "#profile");
	}
	


}