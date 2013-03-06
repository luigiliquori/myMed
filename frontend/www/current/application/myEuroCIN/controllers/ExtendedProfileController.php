<?php

/**
 * ExtendedProfileController
 * Handles all the function releated to the Extended Profile:
 *  show, creation, update, delete, 
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
		if(isset($_GET['method'])){
			
			switch ($_GET['method']){
				
				// Edit user extended profile
				case 'edit':
					if(isset($_SESSION['myEuroCIN']))
						$this->renderView("ExtendedProfileEdit");
					break;
				
				// Show a user pforile
				case 'show_user_profile':
					// If the user is not a guest but has not got an Extended 
					// profile forward him to ExtendedProfileCreate View
					if (!isset($_SESSION['myEuroCIN']))
						$this->renderView("ExtendedProfileCreate");
					else{
						if($_GET['user'] != $_SESSION['user']->id){
							debug("OTHER PROFILE");
							$this->showOtherProfile($_GET['user']);
						}else{
							$this->showUserProfile($_GET['user']);
						}
					}
					break;
			}		
		}
	}
	
	
	/**
	 * DefaultMethod
	 */
	function defaultMethod() {
		
		// If the user is not a guest but has not got an Extended
		// profile forward him to ExtendedProfileCreate View
		if (!isset($_SESSION['myEuroCIN'])){
			$this->renderView("ExtendedProfileCreate");
		}else{
			debug("Default method");
			$this->showUserProfile($_SESSION['user']->id);
		}
	}
	
	
	/** 
	 * Create a new Extended Profile 
	 */
	public function create() {
			
		/* Check mandatory fields
		if (!$_POST['checkCondition']) {
			$this->error = _("Please accept Terms & Conditions");
			$this->renderView("ExtendedProfileCreate");
		}
		*/
		if (!$_POST['phone']) {
			$this->error = _("Please provide a phone number");
			$this->renderView("ExtendedProfileCreate");
		}
		if (!$_POST['desc']) {
			$this->error = _("Description field can't be empty");
			$this->renderView("ExtendedProfileCreate");
		}
		// Unset post vale that we don't need
		unset($_POST['form']);
		unset($_POST['checkCondition']);
		
		// Set id and description
		$_POST['id'] = hash("md5", time()/*.$_POST['name']*/);
		$_POST['desc'] = nl2br($_POST['desc']);
	
		// Create the new profile
		$publish =  new RequestJson($this,
						array("application"=>APPLICATION_NAME.":profiles",
						"id"=>$_POST['id'], "data"=>$_POST,
						"metadata"=>array(/*"name"=>$_POST['name']*/)),
						CREATE);
		$publish->send();
	
		// Check for errors
		if (!empty($this->error))
			$this->renderView("ExtendedProfileCreate");
	
		$this->createUser($_POST['id']);
	
		// Display the new created Extended Profile
		$this->redirectTo("?action=ExtendedProfile&method=show_user_profile&user=".$_SESSION['user']->id);		
	}
	
	/** 
	 * Update an Extended profile 
	 */
	function update() {
		$_POST['email'] =$_SESSION['user']->email;
		$id = $_SESSION['myEuroCIN']->profile;
		
		$_POST['id'] =$_SESSION['user']->id;
				
		$pass = hash("sha512", $_POST['password']);
		
		// Unset useless $_POST fields 
		unset($_POST['form']);
		unset($_POST['password']);
		
		// Password is required
		if( empty($pass) ){
			// TODO i18n
			$this->error = _("Password field can't be empty");
			$this->renderView("ExtendedProfileEdit");
		}
		$request = new Requestv2("v2/AuthenticationRequestHandler", READ);
		$request->addArgument("login", $_SESSION['user']->login);
		$request->addArgument("password", $pass);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			debug("ERROR1: ".$responseObject->description);
			$this->error = $responseObject->description;
			$this->renderView("ExtendedProfileEdit");
		}
		
		// Update of the profile informations
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
		
		$request = new Requestv2("v2/ProfileRequestHandler", UPDATE, array("user"=>json_encode($profile)));
		try {
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
	
			if($responseObject->status != 200) {
				debug("ERROR2: ".$responseObject->description);
				throw new Exception($responseObject->description);
			} else{
				$_SESSION['user'] = (object) array_merge((array) $_SESSION['user'], $profile);
			}
			
		} catch (Exception $e) {
			debug("ERROR3: ".$e->getMessage());
			$this->error = $e->getMessage();
			$this->renderView("ExtendedProfileEdit");
		}
		
		// Update of the organization profile informations
		$_POST['id'] = $id;
		$_POST['desc'] = nl2br($_POST['desc']);
		$myrep = $_SESSION['myEuroCIN']->reputation; 
		$users = $_SESSION['myEuroCIN']->users;
		$publish =  new RequestJson(
						$this,
						array("application"=>APPLICATION_NAME.":profiles", 
						"id"=>$_POST['id'], 
						"user"=>"noNotification", 
						"data"=>$_POST),
						UPDATE);	
		try{
			$publish->send();
		}catch (Exception $e) {
			debug("ERROR4: ".$e->getMessage());
		}
		
		if (!empty($this->error)) {
			$this->renderView("ExtendedProfileEdit");
		} else {
			debug("Success");
			$this->success = _("Complement profile registered successfully!");
			$_SESSION['myEuroCIN']->details = $_POST;
			$_SESSION['myEuroCIN']->reputation = $myrep;
			$_SESSION['myEuroCIN']->users = $users;
			
			// Redirect to main view
			$this->redirectTo("?action=ExtendedProfile&method=show_user_profile&user=".$_SESSION['user']->id);
		}
		
	}

	
	/** 
	 * Delete an Extended profile and all its releated publication 
	 */
	public function delete() {
		$this->delete_Comments($_SESSION['user']->id);
		$this->deletePublications($_SESSION['user']->id); 
		$this->deleteUser($_SESSION['user']->id);
		
		// Redirect to main view
		$this->redirectTo("?action=main");
	}
	

	/** 
	 * Create a new user 
	 */
	function createUser($profile){
	
		// 2 if the user is admin otherwise 1
		$permission
				= (in_array($_SESSION['user']->email, admins::$mails)) ? 2 : 1;

			
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

	
	/** 
	 * Show the user Extended profile 
	 */
	function showUserProfile($user) {
		// Get the user details
		$user = new User($user);
		try {
			$details = $this->mapper->findById($user);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}

		// Get Extended profile details
		$this->profile = new myEuroCINProfile($details['profile']);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		
		// Get reputation
		$this->profile->parseProfile();
		if (!empty($details['profile'])){
			$this->getReputation($user->id);
			$this->profile->reputation = $this->reputationMap[$user->id];
			$this->nbrates = $this->noOfRatesMap[$user->id];
		}
		
		$this->renderView("ExtendedProfileDisplay");
	}
	
	/**
	 * Show another user Extended profile
	 */
	function showOtherProfile($id) {
		/* basic profile */
		$request = new Requestv2("v2/ProfileRequestHandler", READ , array("userID"=>$id));
		$responsejSon = $request->send();
		$responseObject3 = json_decode($responsejSon);
		
		$this->basicProfile = (array) $responseObject3->dataObject->user;
		
		/*Extended profile */
		$user = new User($id);
		try {
			$details = $this->mapper->findById($user);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		$this->profile = new myEuroCINProfile($details['profile']);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		
		// Get reputation
		$this->profile->parseProfile();
		if (!empty($details['profile'])){
			$this->getReputation($id);
			$this->profile->reputation = $this->reputationMap[$id];
			$this->nbrates = $this->noOfRatesMap[$id];
		}
		$this->renderView("ExtendedProfileDisplay");
	}
	
	/** 
	 * Delete a user and its profile 
	 */
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
		
		// Session myEuroCIN is not still valid
		unset($_SESSION['myEuroCIN']);
		
		$this->success = "done";
	
	}
	
	
	/** 
	 * Delete a user profile 
	 */ 
	function deleteProfile($id){
		
		$publish = new RequestJson(
						$this,
						array("application"=>APPLICATION_NAME.":profiles","id"=>$id),
						DELETE);
		$publish->send();
		
		$this->success = "done";
		$this->renderView("main");
	}
	
	
	/** 
	 * Delete user's publications 
	 */
	function deletePublications($id){
		$search_by_userid = new myEuroCINPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
		
		foreach($result as $publication) : // comments on each publications
			$search_comments_publi = new Comment();
			$search_comments_publi->pred1 = 'comment&'.$publication->getPredicateStr().'&'.$id;
			$comments = $search_comments_publi->find();
			foreach($comments as $comment){
				$comment->delete();
			}
			$publication->delete();
		endforeach;
	}
	
	function delete_Comments($id){ // comments posted by the user
		$search_by_userid = new Comment();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find();
	
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
	
	/**
	 * Get the reputation of the user in each application
	 * @param unknown $applicationList
	 */
	private function getReputation($id) {
		$request = new Request("ReputationRequestHandler", READ);
		$request->addArgument("application",  APPLICATION_NAME);
		$request->addArgument("producer",  $id);
		$request->addArgument("consumer",  $_SESSION['user']->id);
	
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		if (isset($responseObject->data->reputation)) {
			$value =  json_decode($responseObject->data->reputation) * 100;
		} else {
			$value = 100;
		}
	
		// Save reputation values
		$this->reputationMap[$id] = $value;
		$this->noOfRatesMap[$id] = $responseObject->dataObject->reputation->noOfRatings;
	}
}
