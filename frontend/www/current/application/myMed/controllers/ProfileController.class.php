<?php 

define('EXTENDED_PROFILE_PREFIX' , 'extended_profile_');
define('STORE_PREFIX' , 'store_');

// TODO: Should be a common controller in /system/controllers/
class ProfileController extends AuthenticatedController {
	

	public function update() {
		$this->renderView('updateProfile');
	}
	
	public function defaultMethod() {
		$this->renderView("profile");
	}
	
	public function handleRequest() { 
		
		parent::handleRequest();
		
		// Check if delete or update
		if($_GET['method'] == 'delete') {
			
			$this->delete();
			
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST") { // UPDATE PROFILE
			
			$_POST["email"] = strtolower(trim($_POST["email"]));
			if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false){
				$this->error = _("Email not valid");
				$this->renderView("updateProfile");
			}
			
			if (isset($_POST['passwordConfirm'])){ // no email, no login
				$mAuthenticationBean = new MAuthenticationBean();
				$mAuthenticationBean->login = $_SESSION['user']->id;
				$mAuthenticationBean->user = $_SESSION['user']->id;
				$mAuthenticationBean->password = hash('sha512', $_POST["passwordConfirm"]);
				unset($_POST['passwordConfirm']);
				$request = new Requestv2("v2/AuthenticationRequestHandler", UPDATE,
					array("authentication"=>json_encode($mAuthenticationBean)));
				try {
					$res = $request->send();
				} catch (Exception $e){
					$this->setError($res->description);
					$this->renderView("profile");
				}
				
				/*
				 * @TODO check if there is an account with MYMED_$_POST["email"]
				 * if yes prompt the user if he wants to merge his accounts
				 * ask for MYMED's acount password, merge MYMED's profile, update old profile with "merged": MYMED profile id
				 */ 
				
			}
			
			$_POST['name'] = $_POST["firstName"] . " " . $_POST["lastName"];
			
			debug_r($_POST);
			$request = new Requestv2("v2/ProfileRequestHandler", UPDATE, array("user"=>json_encode($_POST))
			);
			
			try {
				
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
		
				if($responseObject->status != 200) {
					throw new Exception($responseObject->description);
				} else{
					$_SESSION['user'] = (object) array_merge( (array) $_SESSION['user'], $_POST);
					$this->success = _("Your profile has been successfully updated!");
				}
				
			} catch (Exception $e) {
				$this->error = $e->getMessage();
				$this->renderView("updateProfile");
			}
		}

	}
	
	/** Delete a user profile */
	public function delete() {
		
		// myEurope
		$this->deleteMyEuropeProfile($_SESSION['user']->id);
		// myRiviera
		$this->deleteMyRivieraProfile($_SESSION['user']->id);
		// myFSA
		$this->deleteMyFSAProfile($_SESSION['user']->id);
		// myEuroCIN
		$this->deleteMyEuroCINProfile($_SESSION['user']->id);
		// myEdu
		$this->deleteMyEduProfile($_SESSION['user']->id);
		// myBenevolat
		$this->deleteMyBenevolatProfile($_SESSION['user']->id);
		
		
		// Delete myMed profile
		$request = new Requestv2("v2/AuthenticationRequestHandler",
								 DELETE);
		$request->addArgument("login", str_replace("MYMED_", "", $_SESSION['user']->id)); 
		$pass = hash("sha512", $_POST['password']);
		$request->addArgument("password", $pass);
		$responsejSon = $request->send();
		$response = json_decode($responsejSon);
		
		if($response->status != 200) {
			$this->error = $response->description;
			$this->renderView("profile");
		} else {
			// Logout
			$this->forwardTo('logout');
		}
		
	}
	
	
	/* ********************************************************* */
	/* Functions to delete extended profiles and releated stuffs */
	/* ********************************************************* */
	
	function deleteMyEuropeProfile($id){
		
		// Delete publications
		require MYMED_ROOT . "/application/myEurope/models/Partnership.class.php";
		$search_by_userid = new Partnership();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find(100, "myEurope");
		foreach($result as $item) :
		$item->delete("myEurope");
		endforeach;
		
		// Delete Extended Profile
		$this->deleteExtendedProfile($id, "myEurope");
		
	}
	
	function deleteMyEuroCINProfile($id) {
	
		// Delete comments
		require MYMED_ROOT . "/application/myEuroCIN/models/Comment.class.php";
		$search_by_userid = new Comment();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find(100, "myEuroCIN");
		foreach($result as $item) :
			$item->delete("myEuroCIN");
		endforeach;
		
		// Delete publications
		require MYMED_ROOT . "/application/myEuroCIN/models/myEuroCINPublication.class.php";
		$search_by_userid = new myEuroCINPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find(100, "myEuroCIN");
		
		foreach($result as $publication) :
			// comments on each publications
			$search_comments_publi = new Comment();
			$search_comments_publi->pred1 = 'comment&'.$publication->getPredicateStr().'&'.$id;
			$comments = $search_comments_publi->find(100, "myEuroCIN");
			foreach($comments as $comment){
				$comment->delete("myEuroCIN");
			}
			$publication->delete("myEuroCIN");
		endforeach;
		
		// Delete Extended Profile
		$this->deleteExtendedProfile($id, "myEuroCIN");
	
	}
	
	function deleteMyEduProfile($id) {
		
		// Delete applies
		require MYMED_ROOT . "/application/myEdu/models/Apply.class.php";
		$search_by_userid = new Apply();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find(100, "myEdu");
		foreach($result as $item) :
			$item->delete("myEdu");
		endforeach;
		
		// Delete comments
		$search_by_userid = new Comment();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find(100, "myEdu");
		foreach($result as $item) :
			$item->delete("myEdu");
		endforeach;
		
		// Delete publications
		require MYMED_ROOT . "/application/myEdu/models/MyEduPublication.class.php";
		$search_by_userid = new MyEduPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find(100, "myEdu");
		
		foreach($result as $publication) :
			$search_applies_publi = new Apply();
			$search_applies_publi->pred1 = 'apply&'.$publication->getPredicateStr().'&'.$id;
			$applies = $search_applies_publi->find(100, "myEdu");
			foreach($applies as $apply){
				$apply->delete("myEdu");
			}
			
			$search_comments_publi = new Comment();
			$search_comments_publi->pred1 = 'comment&'.$publication->getPredicateStr().'&'.$id;
			$comments = $search_comments_publi->find(100, "myEdu");
			foreach($comments as $comment){
				$comment->delete("myEdu");
			}
			$publication->delete("myEdu");
		endforeach;
		
		// Remove subscriptions
		$request = new Request("SubscribeRequestHandler", DELETE);
		$request->addArgument("application", "myEdu");
		$request->addArgument("userID", $id);
		$request->send();
		
		//remove subscription object
		require MYMED_ROOT . "/application/myEdu/models/MyEduSubscriptionBean.class.php";
		$deleteObject = new MyEduSubscriptionBean();
		$deleteObject->publisherID = $id;
		$deleteObject->publisher = $id;
		$deleteObject->delete("myEdu");
		
		// Delete Extended Profile
		$this->deleteExtendedProfile($id, "myEdu");
		

	}
	
	function deleteMyBenevolatProfile($id) {
		
		// Delete announcement
		require MYMED_ROOT . "/application/myBenevolat/models/Annonce.class.php";
		$search_by_userid = new Annonce();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find(100, "myBenevolat");
		
		foreach($result as $annonce) :
			$search_applies_annonce = new myBenevolatApply ();
			$search_applies_annonce->pred1 = 'apply&'.$annonce->id.'&'.$id;
			$applies = $search_applies_annonce->find(100, "myBenevolat");
			foreach($applies as $apply){
				$apply->delete("myBenevolat");
			}
			$annonce->delete();
		endforeach;
		
		// Delete applyes
		require MYMED_ROOT . "/application/myMed/models/duplicates/myBenevolatApply.class.php";
		$search_by_userid = new myBenevolatApply();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find(100, "myBenevolat");
		
		foreach($result as $item) :
			$item->delete("myBenevolat");
		endforeach;
		
		// Delete Extended profile
		$this->deleteExtendedProfile($id, "myBenevolat");
		
	}
	
	
	function deleteMyRivieraProfile($id) {
		// TODO: to be implemented
		return;
	}
	
	
	function deleteMyFSAProfile($id) {
		
		$request = new Requestv2("v2/ProfileRequestHandler", 
								 DELETE, 
								 array("userID"=>$id));
		
		$responsejSon = $request->send();
		$responseObject2 = json_decode($responsejSon);
		
		if($responseObject2->status != 200) {
			$this->error = $responseObject2->description;
		} 
			
	}
	
	/** Delete an application extended profile */
	function deleteExtendedProfile($id, $application_name) {

		$find = new RequestJson($this,
				array("application"=>$application_name.":users",
						"id"=>$id));
		try{
			$user = $find->send();
		} catch(Exception $e){
		}
		
		if (isset($user)) {
			$publish = new RequestJson(
					$this,
					array("application"=>$application_name.":profiles",
							"id"=>$user->details->profile,
							"field"=>"user_".$id),
					DELETE);
			$publish->send();
		}
		
		$publish = new RequestJson(
				$this,
				array("application"=>$application_name.":users","id"=>$id),
				DELETE);
		
		$publish->send();
		
	}
	
}
?>