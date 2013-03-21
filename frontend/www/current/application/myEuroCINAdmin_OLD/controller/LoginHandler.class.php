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
require_once '../../lib/socialNetworkAPIs/SocialNetworkConnection.class.php';

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class LoginHandler implements IRequestHandler {

	private /*string*/ $error;
	private /*string*/ $success;
	private /*SocialNetworkConnection*/ $socialNetworkConnection;

	/**
	 *
	 * Enter description here ...
	 */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->socialNetworkConnection = new SocialNetworkConnection();
		$this->handleRequest();
	}

	/**
	 *
	 * Enter description here ...
	 */
	public /*String*/ function handleRequest() {
		if(isset($_POST['refreshUserSession'])) {
			unset($_SESSION['user']);
		}
		
		// Create or Refresh the user session
		if(!isset($_SESSION['user'])) {
			
			// LOGIN
			if(isset($_GET['accessToken']) || isset($_POST['accessToken'])) {
				$accessToken = isset($_GET['accessToken']) ? $_GET['accessToken'] : $_POST['accessToken'];

				if(isset($_GET['registration'])) {
					// HANDLER REGISTRATION
					$request = new Request("AuthenticationRequestHandler", CREATE);
					$request->addArgument("accessToken", $accessToken); 

					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);

					if($responseObject->status != 200) {
						$_SESSION['error'] = $responseObject->description;
					} else {
						header("Refresh:0;url=" . $url . "/" . APPLICATION_NAME . "?inscription=ok"); // REDIRECTION
					}
				} else { // HANDLE LOGIN
					$request = new Request("SessionRequestHandler", READ);
					$request->addArgument("accessToken", $accessToken);
					if(isset($_GET['socialNetwork'])){
						$request->addArgument("socialNetwork", $accessToken);
					} else {
						$request->addArgument("socialNetwork", "myMed");
					}

					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);

					if($responseObject->status != 200) {
						$_SESSION['error'] = $responseObject->description;
					} else {
						$_SESSION['user'] = json_decode($responseObject->data->user);
						if(!isset($_SESSION['friends'])){
							$_SESSION['friends'] = array();
						}
						$_SESSION['accessToken'] = $accessToken;
					}
				}

			} else if(isset($_POST['signin'])) {
				// HANDLE MYMED AUTHENTICATION
				// Preconditions
				if($_POST['login'] == ""){
					$this->error = "FAIL: eMail cannot be empty!";
					return;
				} else if($_POST['password'] == ""){
					$this->error = "FAIL: password cannot be empty!";
					return;
				}
					
				$request = new Request("AuthenticationRequestHandler", READ);
				$request->addArgument("login", $_POST["login"]);
				$request->addArgument("password", hash('sha512', $_POST["password"]));
					
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
					
				if($responseObject->status != 200) {
					$_SESSION['error'] = $responseObject->description;
				} else {
					$accessToken = $responseObject->data->accessToken;
					$url = $responseObject->data->url;
					echo "<form id='signinRedirectForm' name='signinRedirectForm' method='post'>";
					echo "<input type='hidden' name='accessToken' value='" . $accessToken . "' />";
					echo "</form>";
					echo '<script type="text/javascript">document.signinRedirectForm.submit();</script>';
				}

			}
		} else if(!isset($_SESSION['socialNetworkEnabled'])) {
			foreach($this->socialNetworkConnection->getWrappers() as $wrapper) {
				$wrapper->handleAuthentication();
			}
		}

	}

	/**
	 *
	 * Enter description here ...
	 */
	public /*String*/ function getError(){
		return $this->error;
	}

	/**
	 *
	 * Enter description here ...
	 */
	public /*String*/ function getSuccess(){
		return $this->success;
	}

	/**
	 *
	 * Enter description here ...
	 */
	public /*ISocialNetwork[]*/ function getSocialNetworks(){
		return $this->socialNetworks;
	}
}
?>