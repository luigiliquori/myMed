<?php

// TODO: Should be a common controller in /system/controllers/
class LoginController extends AbstractController {

	/**
	 * Create a session if the logins are correct
	 * Returns nothing but populate $_SESSION['accessToken'] in case of success.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {
		$app_id = "352079521548536";
		$app_secret = "c386710770c974bdb307e87d4a8fb4a6";
		$my_url = "http://mymed21.sophia.inria.fr/";
		
		if(isset($_REQUEST["code"])){
			$code = $_REQUEST["code"];
			if(isset($_SESSION['state'])&&($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state']))) {
				$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $app_secret . "&code=" . $code;
			
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
			
				$graph_url = "https://graph.facebook.com/me?access_token="
				. $params['access_token'];
			
				$user = json_decode(file_get_contents($graph_url));
				//$_SESSION['user']= $user->name;
				
				debug_r($user);
			}
		}
		
		/* authed by social networks apis*/
		if (isset($_SESSION['user'])) { 

			debug($_SESSION['accessToken']);
			debug_r($_SESSION['user']);
			$this->storeUser($_SESSION['user'], isset($_SESSION['accessToken'])?$_SESSION['accessToken']:null);
			
			// Redirect to main page
			$this->redirectTo("main");
		
		}
		
		/* Typical login : we received a POST with login and password */
		else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
			// Get arguments 
			$login	= trim($_POST['login']);
			$pass	= hash("sha512", $_POST['password']);
		
			// Login and password should not be empty
			if( empty($login) ){
				// TODO i18n
				$this->error = "Le champ email ne peut pas être vide";
				$this->renderView("login");
			} else if( empty($pass) ){
				// TODO i18n
				$this->error = "Le champ mot de passe ne peut pas être vide!";
				$this->renderView("login");
			}
					
			// Building the Authentication request
			$request = new Requestv2("v2/AuthenticationRequestHandler");
			$request->addArgument("login", $login); // must strtolower here first and if error check with <
			$request->addArgument("password", $pass);
			
			// Argument code filled by Request
			
			// Sending request
			$responsejSon = $request->send();
			
			$responseObject = json_decode($responsejSon);
			
			// the login doesn't exist
			if($responseObject->status == 404) {
				// try in lowercase
				$request->addArgument("login", strtolower($login));
				
				// Sending request
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
			}
			
			// wrong password
			if($responseObject->status == 403) {
				$this->error = "Mot de pass incorrect! <a href='?action=resetPassword&login=" . urlencode($login) . "' rel='external'>Pertes identifiants?</a>";
					
				// Show the login form
				$this->renderView("login");
			
			} else if($responseObject->status != 200) {

				// Save the error
				$this->error = $responseObject->description;
				//debug("error");	
					
				// Show the login form
				$this->renderView("login");
				
			} else {
				
				// Everything went fine, we have now an accessToken in the session
				$_SESSION['accessToken'] = $responseObject->dataObject->accessToken;
				
				// Set user into $_SESSION
				$this->getUserFromSession();
				
				// FIX THE LOGIN TO LOWER CASE IF IT'S NEEDED
				if (strtolower($login) != $login) {
					// create the authentication
					$mAuthenticationBean = new MAuthenticationBean();
					$mAuthenticationBean->login =  strtolower($login); // LOWER CASE LOGIN
					$mAuthenticationBean->user = $_SESSION['user']->id;
					$mAuthenticationBean->password = hash('sha512', $_POST["password"]);
					
					$request = new Requestv2("v2/AuthenticationRequestHandler", CREATE);
					$request->addArgument("authentication", json_encode($mAuthenticationBean));
					$request->addArgument("user", json_encode($_SESSION['user']));
					$request->addArgument("application", APPLICATION_NAME);
						
					// Sending request => Force to create a new account
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
				}
				
				// Redirect to main page
				$this->redirectTo("main");
			}
			
		} else { // Not a POST request : 

			$this->renderView("login");
			
		}

	}

	/**
	 * Get the jSon of the user by using the Session existing (or not) in the Backend.
	 * Returns nothing but populate $_SESSION['user']
	 */
	public /*user*/ function getUserFromSession() {
		
		// Building the Session Request
		// This will check if the session exists in the backend and will return an User if it's the case.	
		$request = new Requestv2("v2/SessionRequestHandler");
		$request->addArgument("socialNetwork", "myMed");
		
		// The AccessToken is fetched from the $_SESSION
		
		// Sending request
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
	
		// In case of errors
		if($responseObject->status != 200) {
			$_SESSION['error'] = $responseObject->description;
		} else {
			// Everything went fine, we now have an USER in our session
			$_SESSION['user'] = (object) array_map('trim', (array) $responseObject->dataObject->user);
			if( !isset($_SESSION['friends']) ){
				$_SESSION['friends'] = array();
			}
		}
	}
	
	public function storeUser($user, $accessToken) {

		$request = new Requestv2("v2/SessionRequestHandler", UPDATE , array("user"=>$user->id, "accessToken"=>$accessToken));
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status != 200) {
			$this->error = $responseObject->description;
		}		
		
		//temp  @TODO see how to merge accounts of other providers with mymed for same emails
		$request = new Requestv2("v2/ProfileRequestHandler", UPDATE , array("user"=>json_encode($user)));
		$responsejSon = $request->send();
		$responseObject2 = json_decode($responsejSon);
		
		$request = new Requestv2("v2/ProfileRequestHandler", READ , array("userID"=>$user->id));
		$responsejSon = $request->send();
		$responseObject3 = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$_SESSION['user2'] = $_SESSION['user']; // 
			$_SESSION['user'] = (object) array_map('trim', (array) $responseObject3->dataObject->user);
		}
	}
}
?>