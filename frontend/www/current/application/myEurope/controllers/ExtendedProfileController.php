<?php

class ExtendedProfileController extends ExtendedProfileRequired {

	public function handleRequest(){
		parent::handleRequest();
		$this->mapper = new DataMapper;
	}
	
	function defaultMethod() {
		debug("default method extendProfile");
		if (isset($_GET['user'])){
			$this->showUserProfile($_GET['user']);
		}
		
		if (isset($_GET['id'])){
			$this->showOtherProfile($_GET['id']);
		}
		else if (isset($_SESSION['user']) && $_SESSION['user']->is_guest)
			$this->forwardTo('login');
		
		else if (isset($_GET['link']))
			$this->createUser($_GET['link']);
		
		else if (!isset($_SESSION['myEurope']) || isset($_GET['list'])){
			// Create a new profile
			$this->renderView("ExtendedProfileCreate");
		}
		else if (isset($_GET['edit']))
			$this->editProfile();
		else if (isset($_GET['delete'])){
			$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
			$debugtxt  .= var_export($_SESSION['user']->id, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			debug("rmPublications");
			$this->deletePublications($_SESSION['user']->id); // delete all posted publications by this user before delete him
			$this->deleteUser($_SESSION['user']->id);
		}
		//I don't know why this is not working so I put it on the top
		else if (isset($_GET['user'])){
			$this->showUserProfile($_GET['user']);
		}
		else if (isset($_SESSION['user'])){
			$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
		}
		else{
			$this->forwardTo("logout");
		}
	}
	
	function update(){
		$this->updateProfile();
	}
	
	function create(){
		$profile = $this->storeProfile();
		
		$this->createUser($profile->id);
		$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
	}
	
	function delete(){
		if (isset($_GET['rmUser']))
			$this->deleteUser($_GET['rmUser']);
		else if (isset($_GET['rmProfile']))
			$this->deleteProfile($_GET['rmProfile']);
		
		///////////////////////////////////////////////////
		else if (isset($_GET['rmPublications'])){
			debug("rmPublications");
			$this->deletePublications($_GET['rmPublications']);
		}
	}
	
	function storeProfile(){
	
		// we clear these ones
		unset($_POST['form']);
	
		if(!$_POST['name']){
			$this->error = _("Organization name field can't be empty");
			$this->renderView("ExtendedProfileCreate");
		}
		if(!$_POST['email']){
			$this->error = _("Organization email field can't be empty");
			$this->renderView("ExtendedProfileCreate");
		}
		/*if(!$_POST['checkCondition']){
			$this->error = _("You must accept the terms of use.");
			$this->renderView("ExtendedProfileCreate");
		}*/
		$_POST['id'] = hash("md5", time().$_POST['name']);
		$_POST['desc'] = nl2br($_POST['desc']);
		$_POST['territoryType'] = implode("|", $_POST['territoryType']);
		debug($_POST['territoryType']);
		//unset($_POST['checkCondition']);
	
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$_POST['id'], 
						"data"=>$_POST, 
						"metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
				CREATE);
		$publish->send();
	
		if (!empty($this->error))
			$this->renderView("ExtendedProfileCreate");
	
		return (object) $_POST;
	}

	function createUser($profile){
	
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
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", 
						"id"=>$_SESSION['user']->id, 
						"data"=>$user, 
						"metadata"=>$user),
				CREATE);
		$publish->send();
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$profile, 
						"data"=>array("user_".$_SESSION['user']->id=>$_SESSION['user']->id, 
								"email_".$_SESSION['user']->id=> $_SESSION['user']->email)),
				UPDATE);
		$publish->send();
		
		//$_SESSION['myEurope']->profile = $profile;
		$this->success = _("Complement profile registered successfully!");
		
		//subscribe to our profile changes (permission change - partnership req accepted)
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":users", 
						"id"=>$_SESSION['user']->id, 
						"user"=> $_SESSION['user']->id, 
						"mailTemplate"=>APPLICATION_NAME.":userUpdate"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		//subscribe to our organization profile
		$subscribe = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$profile, 
						"user"=> $_SESSION['user']->id, 
						"mailTemplate"=>APPLICATION_NAME.":profileUpdate"),
				CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
	
	}
	
	function updateProfile(){
		if(!$_POST['name']){
			$this->error = _("Organization name field can't be empty");
			$this->renderView("ExtendedProfileEdit");
		}
		if(!$_POST['email']){
			$this->error = _("Organization email field can't be empty");
			$this->renderView("ExtendedProfileEdit");
		}
		
		if(!isset($_SESSION['userFromExternalAuth'])){ // no update basic profile if from a social network
			debug("UPDATE BASIC PROFILE");
			
			$name = $_POST['name'];
			$email = $_POST['email'];
			$id = $_POST['id'];
			$_POST['email']=$_SESSION['user']->email;
			$_POST['id']=$_SESSION['user']->id;
			
			unset($_POST['form']);
			
			// update of the profile informations
			$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
			$_POST['login'] = $_SESSION['user']->email;
			$profile = array (
					"id"=>$_POST['id'],
					"email"=>$_POST['email'],
					"firstName"=>$_POST['firstName'],
					"lastName"=>$_POST['lastName'],
					"name"=>$_POST['name'],
					"login"=>$_POST['login'],
					"birthday"=>$_POST['birthday'],
					"profilePicture"=>$_POST['profilePicture'],
					"lang"=> $_POST['lang']
			);
			unset($_POST['id']);
			unset($_POST['firstName']);
			unset($_POST['lastName']);
			unset($_POST['name']);
			unset($_POST['birthday']);
			unset($_POST['profilePicture']);
			unset($_POST['lang']);
			unset($_POST['email']);
			unset($_POST['login']);
			
			$request = new Requestv2(
				"v2/ProfileRequestHandler", UPDATE, array("user"=>json_encode($profile))
			);
	
			try {
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
		
				if($responseObject->status != 200) {
					throw new Exception($responseObject->description);
				} else{
					debug("DESCRIPTION  ".$responseObject->description);
					$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $profile);
				}
				
			} catch (Exception $e){
				$this->error = $e->getMessage();
				$this->renderView("ExtendedProfileEdit");
			}
			$_POST['name'] = $name; // organization name and not username
			$_POST['email'] = $email; // organization email != profile email
			$_POST['id'] = $id;
		}
		
		$_POST['territoryType'] = implode("|", $_POST['territoryType']);
		
		// update of the organization profile informations
		$_POST['desc'] = nl2br($_POST['desc']);
		$myrep = $_SESSION['myEurope']->reputation; //and reputation
		$users = $_SESSION['myEurope']->users; //and user
		
		debug_r($_POST);
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "data"=>$_POST),
				UPDATE);	
		$publish->send();
		
		$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
		$debugtxt  .= var_export($_POST, TRUE);
		$debugtxt .= "</pre>";
		debug($debugtxt);
		if ($_POST['name']!=$_SESSION['myEurope']->details['name'] || $_POST['role']!=$_SESSION['myEurope']->details['role']){ //also update profiles indexes
			$publish =  new RequestJson($this,
					array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
					CREATE);
			$publish->send();
		}
		
		if (!empty($this->error))
			$this->renderView("ExtendedProfileEdit");
		else {
			$this->success = _("Complement profile registered successfully!");
			$_SESSION['myEurope']->details = $_POST;
			$_SESSION['myEurope']->reputation = $myrep;
			$_SESSION['myEurope']->users = $users;
	
			//$this->redirectTo("Main", array(), "#profile");
			$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
		}
		
	}
	
	
	function editProfile(){
		$this->renderView("ExtendedProfileEdit");
	}
	
	
	function showOtherProfile($id){
		$this->profile = new Profile($id);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		$this->profile->parseProfile();
		$this->profile->reputation = pickFirst(getReputation(array($id)));

		$this->renderView("ExtendedProfileDisplay");
	}
	
	function showUserProfile($user){
		$user = new User($user);
		try {
			$details = $this->mapper->findById($user);
		} catch (Exception $e) {
			error_log("LOGROM search".$e);
			$this->redirectTo("main");
		}
		$this->profile = new Profile($details['profile']);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			error_log("LOGROM search".$e);
			$this->redirectTo("main");
		}
		//debug_r($details);
		$this->profile->parseProfile();
		if (!empty($details['profile'])) $this->profile->reputation = pickFirst(getReputation(array($details['profile'])));
		$this->renderView("ExtendedProfileDisplay");
	}
	
	function showProfileList(){
		$profile = new Profile();
		try {
			$res = $this->mapper->findByPredicate($profile);
		} catch (Exception $e) {
		}
	
		$this->cats = Categories::$roles;
		//debug_r($res);
	
		function filterArray($array, $value){
			$result = array();
			foreach($array as $item) {
				if ($item->role == $value) {
					$result[] = $item;
				}
			}
			return $result;
		}
	
		foreach($this->cats as $k=>$v){
			$this->cats[$k] = filterArray($res, $k);
		}
		$this->renderView("ExtendedProfileList");
	
	}
	
	function deleteUser($id){
		
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
		unset($_SESSION['myEurope']);
		$this->success = "done";
		$this->renderView("main");
	
	}
	
	function deleteProfile($id){
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles","id"=>$id),
				DELETE);
	
		$publish->send();
		$this->success = "done";
		$this->renderView("main");
	
	}
	
	function deletePublications($id){
		
		$search_by_userid = new Partnership();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
		debug("NB PUBLICATION  de ".$search_by_userid->publisher." :".sizeof($result));
		foreach($result as $item) :
			$item->delete();
		endforeach;
		
		//$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
	}
}
