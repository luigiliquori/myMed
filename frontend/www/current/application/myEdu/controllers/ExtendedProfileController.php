<?php

/**
 * ExtendedProfileController 
 *
 */
class ExtendedProfileController extends ExtendedProfileRequired {


	/**
	 * HandleRequest
	 */
	public function handleRequest(){
		
		parent::handleRequest();
		$this->mapper = new DataMapper;
		
		// If the user is a guest, forward to login
		if (isset($_SESSION['user']) && $_SESSION['user']->is_guest) {
			$this->forwardTo('login');
		}
		
		// Execute the called controller method
		switch ($_GET['method'])
		{
			// Edit user extended profile
			case 'edit':
				if(isset($_SESSION['myEdu']))
					$this->renderView("ExtendedProfileEdit");
				break;
		}		
	}
	
	
	/**
	 * DefaultMethod
	 */
	function defaultMethod() {
		

		// What the hell ?
		if (isset($_GET['link'])) {
			$this->createUser($_GET['link']);
		}
		
		// If the user is not a guest but has not got an Extended profile
		// forward to ExtendedProfileCreate View 
		else if (!isset($_SESSION['myEdu'])) {
			$this->renderView("ExtendedProfileCreate");
		}
		
		// If the user has an Extended Profile, show it
		else if (isset($_SESSION['user'])) {
			$this->showUserProfile($_SESSION['user']->id);
		} else {
			$this->forwardTo("logout");
		}
	}
	
	
	/** Create a new Extended Profile */
	public function create() {
			
		// Check form fiels
		if(!$_POST['checkCondition']){
			$this->error = _("You must accept the terms of use.");
			$this->renderView("ExtendedProfileCreate");
		}
	
		// Unset post vale that we don't need
		unset($_POST['form']);
		unset($_POST['checkCondition']);
		// Set id and description
		$_POST['id'] = hash("md5", time().$_POST['name']);
		$_POST['desc'] = nl2br($_POST['desc']);
	
		// Create the new profile
		$publish =  new RequestJson($this,
						array("application"=>APPLICATION_NAME.":profiles",
						"id"=>$_POST['id'], "data"=>$_POST,
						"metadata"=>array("role"=>$_POST['role'],
						"name"=>$_POST['name'])),
						CREATE);
		$publish->send();
	
		// Check for errors
		if (!empty($this->error))
			$this->renderView("ExtendedProfileCreate");
	
		$this->createUser($_POST['id']);
	
		// Display the new created Extended Profile
		$this->redirectTo("?action=ExtendedProfile");		
	}
		
	
	/** Update an Extended profile */
	public function update() {
		
		$name = $_POST['name'];
		$email = $_POST['email'];
		$id = $_SESSION['myEdu']->profile;
		$_POST['email'] =$_SESSION['user']->email;
		$_POST['id'] =$_SESSION['user']->id;
				
		$pass	= hash("sha512", $_POST['password']);
		
		// Unset useless $_POST fields 
		unset($_POST['form']);
		unset($_POST['password']);
		
		// Password is required
		if( empty($pass) ){
			// TODO i18n
			$this->error = _("Email field can't be empty");
			$this->renderView("ExtendedProfileEdit");
		}
		$request = new Requestv2("v2/AuthenticationRequestHandler", READ);
		$request->addArgument("login", $_SESSION['user']->login);
		$request->addArgument("password", $pass);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
			$this->renderView("ExtendedProfileEdit");
		}
		
		// Update of the profile informations
		$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
		$_POST['login'] = $_SESSION['user']->email;
		$request = new Requestv2("v2/ProfileRequestHandler",
								 UPDATE,
								 array("user"=>json_encode($_POST))
								);
		try {
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
	
			if($responseObject->status != 200) {
				throw new Exception($responseObject->description);
			} else{
				$profile = array ("id"=>$_POST['id'], 
								  "name"=>$_POST['name'],
								  "firstName"=>$_POST['firstName'], 
								  "lastName"=>$_POST['lastName'], 
								  "birthday"=>$_POST['birthday'], 
								  "profilePicture"=>$_POST['profilePicture'], 
								  "lang"=> $_POST['lang']);
				$_SESSION['user'] 
					= (object) array_merge((array) $_SESSION['user'], $profile);
			}
			
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->renderView("ExtendedProfileEdit");
		}
		
		$_POST['name'] = $name; // organization name and not username
		$_POST['email'] = $email; // organization email != profile email
		$_POST['id'] = $id;
		unset($_POST['firstName']);
		unset($_POST['lastName']);
		unset($_POST['birthday']);
		unset($_POST['profilePicture']);
		unset($_POST['lang']);
		
		// Update of the organization profile informations
		$_POST['desc'] = nl2br($_POST['desc']);
		$myrep = $_SESSION['myEdu']->reputation; 
		$users = $_SESSION['myEdu']->users;
		$publish =  new RequestJson(
						$this,
						array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$_POST['id'], 
						"user"=>"noNotification", 
						"data"=>$_POST),
						UPDATE);	
		$publish->send();
		
		if ($_POST['name']!= $_SESSION['myEurope']->details['name'] || 
			$_POST['role']!=$_SESSION['myEurope']->details['role']) { 
			
			//also update profiles indexes
			$publish =  new RequestJson(
							$this,
							array("application"=>APPLICATION_NAME.":profiles", 
							"id"=>$_POST['id'], 
							"user"=>"noNotification", 
							"metadata"=>array("role"=>$_POST['role'], 
							"name"=>$_POST['name'])),
							CREATE);
			$publish->send();
		}
		
		if (!empty($this->error)) {
			$this->renderView("ExtendedProfileEdit");
		} else {
			
			$this->success = _("Complement profile registered successfully!");
			$_SESSION['myEdu']->details = $_POST;
			$_SESSION['myEdu']->reputation = $myrep;
			$_SESSION['myEdu']->users = $users;
			
			// Redirect to main view
			$this->redirectTo("Main", array(), "#profile");
		}
		
	}

	
	/** Delete an Extended profile and all its releated publication */
	public function delete() {
		$this->deletePublications($_SESSION['user']->id); 
		$this->deleteUser($_SESSION['user']->id);
	}
	

	/** Create a new user */
	function createUser($profile){
	
		// Permission is 2 if the user is an admin, otherwise 1
		$permission = (
				strcontain($_SESSION['user']->email, "@inria.fr")
				|| $_SESSION['user']->email=="luigi.liquori@gmail.com"
				|| $_SESSION['user']->email=="bredasarah@gmail.com"
				|| $_SESSION['user']->email=="myalpmed@gmail.com"
		)? 2 : 1;
			
		$user = array(
				'permission'=> $permission,
				'email'=> $_SESSION['user']->email,
				'profile'=> $profile
		);
		
		
		$publish = new RequestJson(
					$this,
					array("application"=>APPLICATION_NAME.":users", 
					"id"=>$_SESSION['user']->id, 
					"data"=>$user, 
					"metadata"=>$user),
					CREATE);
		$publish->send();
		
		$publish = new RequestJson(
					$this,
					array("application"=>APPLICATION_NAME.":profiles", 
					"id"=>$profile, 
					"data"=>array("user_".$_SESSION['user']->id=>$_SESSION['user']->id, 
					"email_".$_SESSION['user']->id=> $_SESSION['user']->email)),
					UPDATE);
		$publish->send();
		
		$this->success = _("Complement profile registered successfully!");
		
		// Subscribe to our profile changes 
		// (permission change - partnership req accepted)
		$subscribe = new RequestJson( 
						$this,
						array("application"=>APPLICATION_NAME.":users", 
						"id"=>$_SESSION['user']->id, 
						"user"=> $_SESSION['user']->id, 
						"mailTemplate"=>APPLICATION_NAME.":userUpdate"),
						CREATE, 
						"v2/SubscribeRequestHandler");
		$subscribe->send();
		
		// Subscribe to our organization profile
		$subscribe = new RequestJson(
						$this,
						array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$profile, "user"=> $_SESSION['user']->id, 
						"mailTemplate"=>APPLICATION_NAME.":profileUpdate"),
						CREATE, 
						"v2/SubscribeRequestHandler");
		$subscribe->send();
	
	}
			
	/** Show a user Extended profile */
	function showUserProfile($user){
		
		// Get the user details
		$user = new User($user);
		try {
			$details = $this->mapper->findById($user);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		
		// Get Extended profile details
		$this->profile = new MyEduProfile($details['profile']);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		
		// Get reputation
		$this->profile->parseProfile();
		if (!empty($details['profile'])) 
			$this->profile->reputation = pickFirst(getReputation(array($details['profile'])));
		
		$this->renderView("ExtendedProfileDisplay");
	}
	
	
	/** Delete a user and its profile */
	function deleteUser($id) {
		
		// Delete the user if exists
		$find = new RequestJson($this, 
								array("application"=>APPLICATION_NAME.":users",
								"id"=>$id));
		try{ 
			$user = $find->send();
		} catch(Exception $e) {}
		if (isset($user)){
			$publish = new RequestJson(
							$this,
							array("application"=>APPLICATION_NAME.":profiles",
							"id"=>$user->details->profile, 
							"field"=>"user_".$id),
							DELETE);
			$publish->send();
		}
	
		$publish = new RequestJson($this,
					array("application"=>APPLICATION_NAME.":users",
					"id"=>$id),
					DELETE);
		$publish->send();
		
		// Session myEdu is not still valid
		unset($_SESSION['myEdu']);
		
		$this->success = "done";
		$this->renderView("main");
	
	}
	
	
	/** Delete a user profile */ 
	function deleteProfile($id){
		
		$publish = new RequestJson(
						$this,
						array("application"=>APPLICATION_NAME.":profiles","id"=>$id),
						DELETE);
		$publish->send();
		
		$this->success = "done";
		$this->renderView("main");
	}
	
	
	/** Delete user's publications */
	function deletePublications($id){
		
		$search_by_userid = new MyEduPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
		
		foreach($result as $item) :
			$item->delete();
		endforeach;
		
		//$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
	}
}
