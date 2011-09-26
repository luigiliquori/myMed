<?php require_once 'system/request/Request.class.php'; ?>
<?php require_once 'system/handler/IRequestHandler.php'; ?>
<?php require_once 'system/beans/MUserBean.class.php'; ?>
<?php require_once 'system/beans/MAuthenticationBean.class.php'; ?>

<?php 
class LoginHandler implements IRequestHandler {
	
	private /*string*/ $error;
	private /*string*/ $success;
	
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
	}
	
	public function get_facebook_cookie($app_id, $application_secret) {
		$args = array();
		parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
		ksort($args);
		$payload = '';
		foreach ($args as $key => $value) {
			if ($key != 'sig') {
				$payload .= $key . '=' . $value;
			}
		}
		if (md5($payload . $application_secret) != $args['sig']) {
			return null;
		}
		return $args;
	}
	
	public /*String*/ function handleRequest() { 
		// TRY TO LOGIN
		if(isset($_POST['singin'])) { 
			// Preconditions
			if($_POST['login'] == ""){
				$this->error = "FAIL: eMail cannot be empty!";
				return;
			} else if($_POST['password'] == ""){
				$this->error = "FAIL: password cannot be empty!";
				return;
			}
			
			// AUTHENTICATION
			$request = new Request("AuthenticationRequestHandler", READ);
			$request->addArgument("login", $_POST["login"]);
			$request->addArgument("password", hash('sha512', $_POST["password"]));
			$response = $request->send();
			// Check if there's not error
			$check = json_decode($response);
			if(isset($check->error)) {
				$_SESSION['error'] = $check->error->message;
				return;
			} else if($check->firstName != null) {
				$user = $check;
				// AUTHENTENTICATION OK: CREATE A SESSION
				$request = new Request("SessionRequestHandler", CREATE);
				$request->addArgument("userID", $user->id);
				$response = $request->send();
				$check = json_decode($response);
				if(isset($check->error)) {
					$_SESSION['error'] = $check->error->message;
					return;
				} else {
					$_SESSION['user'] = $user;
					return;
				}
			}
		}
		
		// TEST
		// FACEBOOK USER AUTHENTICATION ----------------------------------------
// 		$cookie = $this->get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
// 		if ($cookie['access_token'] && !$_SESSION['logged']) {
// 			$facebook_user = json_decode(file_get_contents(
// 		   			   'https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
		
// 			$_SESSION['user'] = json_decode('{
// 				  "id":  "facebook' . $facebook_user->id . '",
// 				  "firstName": "' . $facebook_user->first_name . '",
// 				  "lastName": "' . $facebook_user->last_name . '",
// 				  "email": "' . $facebook_user->email . '",
// 				  "birthday": "' . $facebook_user->birthday . '",
// 		 		  "gender": "' . $facebook_user->gender . '",
// 				  "locale": "' . $facebook_user->locale . '",
// 				  "updated_time": "' . $facebook_user->updated_time . '",
// 				  "profile": "http://www.facebook.com/profile.php?id=' . $facebook_user->id . '",
// 				  "profilePicture" : "http://graph.facebook.com/' . $facebook_user->id . '/picture?type=large",
// 				  "socialNetwork" : "facebook"
// 				}');		
		
// 			$_SESSION['friends'] = json_decode(file_get_contents(
// 		   		   				 'https://graph.facebook.com/me/friends?access_token=' .
// 			$cookie['access_token']))->data;
		
// 		}
		// --------------------------------------------------------------------------
	}
	
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>