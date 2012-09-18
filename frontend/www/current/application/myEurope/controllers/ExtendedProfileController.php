<?php

require_once 'profile-utils.php';

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
			if ($_POST["form"] == 'edit')
				$this->updateProfile();
			else {
				$profile = $this->storeProfile();
				debug_r($profile);
				$this->redirectTo("ExtendedProfile", array("id"=>$profile->id, "link"=>""));
			}
		}
		
		else if (isset($_GET['edit']))
			$this->editProfile();
		else if (isset($_GET['id']))
			$this->showOtherProfile($_GET['id']);
		else if (isset($_GET['link']))
			$this->createUser($_GET['link']);
		else if (isset($_GET['user']))
			$this->showUserProfile($_GET['user']);
		else if (isset($_GET['rmUser']))
			$this->deleteUser($_GET['rmUser']);
		
		else if (isset($_GET['rmProfile']))
			$this->deleteProfile($_GET['rmProfile']);
		
		else if (isset($_SESSION['myEurope'])){
			$this->showMyProfile();
			debug('k');
		}
		
			
		else
			$this->renderView("ExtendedProfileCreate");
		
	}
	
	/**
	 * 
	 */
	public /*void*/ function createUser($profile){
	
		$permission = (
				strcontain($_SESSION['user']->email, "@inria.fr")
				|| $_SESSION['user']->email=="luigi.liquori@gmail.com"
				|| $_SESSION['user']->email=="bredasarah@gmail.com"
				|| $_SESSION['user']->email=="myalpmed@gmail.com"
		)? 2 : 0;
			
		$user = array(
				'permission'=> $permission,
				'email'=> $_SESSION['user']->email,
				'profile'=> $profile
		);
		
		debug_r($user);
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id, "data"=>$user, "metadata"=>$user),
				CREATE);
		$publish->send();
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$profile, "data"=>array("user_".$_SESSION['user']->id=>$_SESSION['user']->id, "email_".$_SESSION['user']->id=> $_SESSION['user']->email)),
				UPDATE);
		$publish->send();
		
		//$_SESSION['myEurope'] = new stdClass();
		//$_SESSION['myEurope']->permission = $permission;
		//$profile->{"user".$_SESSION['user']->id} = $_SESSION['user']->id;
		
		//$_SESSION['myEurope']->profile = $profile;
			//debug_r($_SESSION['myEurope']->profile);
		$this->success = "Complément de profil enregistré avec succès!";

		//debug_r($_SESSION['myEurope']);
		
		//subscribe to our profile changes (permission change - partnership req accepted)
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":userUpdate"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		//subscribe to our organization profile
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$profile, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":profileUpdate"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		
		if ($permission <= 0)
			$this->renderView("WaitingForAccept");
		else
			$this->renderView("main");
	
	}
	
	public /*void*/ function updateProfile(){
		$pass	= hash("sha512", $_POST['password']);
		unset($_POST['form']);
		unset($_POST['password']);
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
		
		$_POST['desc'] = nl2br($_POST['desc']);
		$myrep = $_SESSION['myEuropeProfile']->reputation; //and reputation
		$users = $_SESSION['myEuropeProfile']->users; //and user
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "data"=>$_POST),
				UPDATE);
		$publish->send();
		
		if ($_POST['name']!=$_SESSION['myEuropeProfile']->name || $_POST['role']!=$_SESSION['myEuropeProfile']->role){ //also update profiles indexes
			$publish =  new RequestJson($this,
					array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
					CREATE);
			$publish->send();
		}
		
		if (!empty($this->error))
			$this->renderView("ExtendedProfileEdit");
		else {
			$this->success = "Complément de profil enregistré avec succès!";
			$_SESSION['myEuropeProfile'] = (object) $_POST;
			$_SESSION['myEuropeProfile']->reputation = $myrep;
			$_SESSION['myEuropeProfile']->users = $users;
	
			$this->redirectTo("Main", array(), "#profile");
	
		}
	}
	
	public /*void*/ function storeProfile(){
		
		debug($_SESSION['user']->email);
		
		// we clear these ones
		unset($_POST['form']);
			
		if(!$_POST['checkCondition']){
			$this->error = "Vous devez accepter les conditions d'utilisation.";
			$this->renderView("ExtendedProfileCreate");
		}
		$_POST['id'] = hash("md5", time().$_POST['name']);
		$_POST['desc'] = nl2br($_POST['desc']);
		unset($_POST['checkCondition']);
		//debug_r($_POST);
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "data"=>$_POST, "metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
				CREATE);
		$publish->send();

		if (!empty($this->error))
			$this->renderView("ExtendedProfileCreate");
		
		return (object) $_POST;
	}
	
	/**
	 * Edit the extended profile.
	 * In order to edit it, the user is asked to enter his password, like a SUDO permission.
	 * This prevent the user from editing critical informations
	 */
	public /*void*/ function editProfile(){
		
		$this->renderView("ExtendedProfileEdit");
		
	}
	
	public /*void*/ function showOtherProfile($id){
		debug_r($id);
		
		$this->profile = new ExtendedProfile($this, $id);
		try{
			$this->result = $this->profile->readProfile();
		}catch (NoResultException $e) {
		}catch(Exception $e){
		}

		//debug_r($this->profile);
		$this->renderView("ExtendedProfileDisplay");
	}
	
	public /*void*/ function showUserProfile($id){
	
		$this->profile = getProfilefromUser($this, $id);
		if (!empty($this->profile)){
			$this->id = $this->profile->name;
			$this->renderView("ExtendedProfileDisplay");
		}
	}
	
	public /*void*/ function showMyProfile(){

		$this->redirectTo("Main", array(), "#profile");
	}
	
	public /*void*/ function deleteUser($id){
		
		//update it's shared profile
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "id"=>$id));
		try{ $user = $find->send();} catch(Exception $e){}
		if (isset($user)){
			$publish = new RequestJson($this,
					array("application"=>APPLICATION_NAME.":profiles","id"=>$user->details->profile, "field"=>"user_".$id),
					DELETE);
			$publish->send();
		}
	
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users","id"=>$id),
				DELETE);
	
		$publish->send();
		exit();
	
	}
	
	public /*void*/ function deleteProfile($id){
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles","id"=>$id),
				DELETE);
	
		$publish->send();
		exit();
	
	}
	


}