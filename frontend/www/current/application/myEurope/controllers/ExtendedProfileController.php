<?php

class ExtendedProfileController extends ExtendedProfileRequired {

	function handleRequest(){
		parent::handleRequest();
		$this->mapper = new DataMapper;
	}
	
	function defaultMethod() {

		if (isset($_GET['edit']))
			$this->editProfile();
		else if (isset($_GET['id']))
			$this->showOtherProfile($_GET['id']);
		else if (isset($_GET['list']))
			$this->showProfileList();
		else if (isset($_GET['new']))
			$this->renderView("ExtendedProfileCreate");
		else if (isset($_GET['link']))
			$this->createUser($_GET['link']);
		else if (isset($_GET['user']))
			$this->showUserProfile($_GET['user']);
		else if (isset($_SESSION['user'], $_SESSION['user']->is_guest) )
			$this->forwardTo('login');
		else if (isset($_SESSION['user']))
			$this->forwardTo("extendedProfile", array("user"=>$_SESSION['user']->id));
		else
			$this->forwardTo("logout");
		
	}
	
	function update(){
		$this->updateProfile();
	}
	
	function create(){
		$profile = $this->storeProfile();
		debug_r($profile);
		$this->redirectTo("ExtendedProfile", array("id"=>$profile->id, "link"=>""));
	}
	
	function delete(){
		if (isset($_GET['rmUser']))
			$this->deleteUser($_GET['rmUser']);
		else if (isset($_GET['rmProfile']))
			$this->deleteProfile($_GET['rmProfile']);
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
		
		debug_r($user);
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_SESSION['user']->id, "data"=>$user, "metadata"=>$user),
				CREATE);
		$publish->send();
		
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$profile, "data"=>array("user_".$_SESSION['user']->id=>$_SESSION['user']->id, "email_".$_SESSION['user']->id=> $_SESSION['user']->email)),
				UPDATE);
		$publish->send();
		
		//$_SESSION['myEurope']->profile = $profile;
		$this->success = "Complément de profil enregistré avec succès!";
		
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
	
	function updateProfile(){
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
			$this->renderView("ExtendedProfileEdit");
		}
		
		$_POST['desc'] = nl2br($_POST['desc']);
		$myrep = $_SESSION['myEurope']->reputation; //and reputation
		$users = $_SESSION['myEurope']->users; //and user
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "data"=>$_POST),
				UPDATE);
		$publish->send();
		
		if ($_POST['name']!=$_SESSION['myEurope']->details['name'] || $_POST['role']!=$_SESSION['myEurope']->details['role']){ //also update profiles indexes
			$publish =  new RequestJson($this,
					array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "user"=>"noNotification", "metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
					CREATE);
			$publish->send();
		}
		
		if (!empty($this->error))
			$this->renderView("ExtendedProfileEdit");
		else {
			$this->success = "Complément de profil enregistré avec succès!";
			$_SESSION['myEurope']->details = $_POST;
			$_SESSION['myEurope']->reputation = $myrep;
			$_SESSION['myEurope']->users = $users;
	
			$this->redirectTo("Main", array(), "#profile");
	
		}
	}
	
	function storeProfile(){
		
		// we clear these ones
		unset($_POST['form']);
			
		if(!$_POST['checkCondition']){
			$this->error = "Vous devez accepter les conditions d'utilisation.";
			$this->renderView("ExtendedProfileCreate");
		}
		$_POST['id'] = hash("md5", time().$_POST['name']);
		$_POST['desc'] = nl2br($_POST['desc']);
		unset($_POST['checkCondition']);

		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles", "id"=>$_POST['id'], "data"=>$_POST, "metadata"=>array("role"=>$_POST['role'], "name"=>$_POST['name'])),
				CREATE);
		$publish->send();

		if (!empty($this->error))
			$this->renderView("ExtendedProfileCreate");
		
		return (object) $_POST;
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
		$this->profile->reputation = pickFirst(parent::getReputation(array($id)));

		$this->renderView("ExtendedProfileDisplay");
	}
	
	function showUserProfile($user){
		$user = new User($user);
		try {
			$details = $this->mapper->findById($user);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		$this->profile = new Profile($details['profile']);
		try {
			$this->profile->details = $this->mapper->findById($this->profile);
		} catch (Exception $e) {
			$this->redirectTo("main");
		}
		debug_r($details);
		$this->profile->parseProfile();
		if (!empty($details['profile'])) $this->profile->reputation = pickFirst(parent::getReputation(array($details['profile'])));
		$this->renderView("ExtendedProfileDisplay");
	}
	
	function showProfileList(){
		$profile = new Profile();
		try {
			$res = $this->mapper->findByPredicate($profile);
		} catch (Exception $e) {
		}
	
		$this->cats = Categories::$roles;
		debug_r($res);
	
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


}