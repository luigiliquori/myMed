<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php 
require_once '../../lib/dasp/request/Request.class.php';
require_once '../../lib/dasp/beans/MUserBean.class.php';
require_once '../../lib/dasp/beans/MAuthenticationBean.class.php';

/**
 * RequestHandler for the profile management
 * @author lvanni
 *
 */
class UpdateProfileHandler implements IRequestHandler {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function handleRequest() { 
		// TRY TO REGISTER A NEW ACCOUNT
		if(isset($_POST['update'])) {
			
			// Preconditions
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "FAIL: password != confirmation";
				return;
			} else if($_POST['password'] == ""){
				$this->error = "FAIL: password cannot be empty!";
				return;
			} else if($_POST['email'] == ""){
				$this->error = "FAIL: email cannot be empty!";
				return;
			}
			
			// update the authentication
			$mAuthenticationBean = new MAuthenticationBean();
			$mAuthenticationBean->login =  $_SESSION['user']->email;
			$mAuthenticationBean->user = $_SESSION['user']->id;
			$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
			
			$request = new Request("AuthenticationRequestHandler", UPDATE);
			$request->addArgument("authentication", json_encode($mAuthenticationBean));
			
			$request->addArgument("oldLogin", $_SESSION['user']->email);
			$request->addArgument("oldPassword", hash('sha512', $_POST["oldPassword"]));
			
			$responsejSon = $request->send();
			$responseObject1 = json_decode($responsejSon);
			
			if($responseObject1->status != 200) {
				$this->error = $responseObject1->description;
				return;
			}
			
			// update the profile
			$mUserBean = new MUserBean();
			$mUserBean->id = $_SESSION['user']->id;
			$mUserBean->firstName = $_POST["prenom"];
			$mUserBean->lastName = $_POST["nom"];
			$mUserBean->name = $_POST["prenom"] . " " . $_POST["nom"];
			$mUserBean->email = $_POST["email"];
			$mUserBean->login = $_POST["email"];
			$mUserBean->birthday = $_POST["birthday"];
			$mUserBean->profilePicture = $_POST["thumbnail"];
			
			// keep the session opened 
			$mUserBean->socialNetworkName = $_SESSION['user']->socialNetworkName;
			$mUserBean->SocialNetworkID = $_SESSION['user']->SocialNetworkID;
			$mUserBean->SocialNetworkID = $_SESSION['accessToken'];
			
			$request = new Request("ProfileRequestHandler", UPDATE);
			$request->addArgument("user", json_encode($mUserBean));
				
			$responsejSon = $request->send();
			$responseObject2 = json_decode($responsejSon);
				
			$responseObject2 = json_decode($responsejSon);
			if($responseObject2->status != 200) {
				$this->error = $responseObject2->description;
				return;
			}
			
			$_SESSION['user'] = json_decode($responseObject2->data->profile);
			header("Refresh:0;url=".$_SERVER['PHP_SELF']);
		}
	}
	
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>