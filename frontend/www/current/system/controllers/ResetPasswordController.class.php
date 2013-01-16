<?php

// TODO: Should be a common controller in /system/controllers/
class ResetPasswordController extends AbstractController {

	/**
	 * Create a session if the logins are correct
	 * Returns nothing but populate $_SESSION['accessToken'] in case of success.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() {

		// SEND A MAIL
		if (isset($_GET['login']) && !isset($_GET['accessToken'])) {
			$request = new Request("AuthenticationRequestHandler", UPDATE);
			$request->addArgument("login",  $_GET['login']);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);

			echo $responsejSon;

			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			} else {
				$this->success = _("An email has been sent to you!");
			}
			$this->renderView("login");

			// PRINT RESET PASS VIEW
		} else if (isset($_GET['login']) && isset($_GET['accessToken'])) {
			$_SESSION['loginToUpdate'] = $_GET['login'];
			$_SESSION['passwordToUpate'] = $_GET['accessToken'];
			$this->renderView("resetPassword");

			// RESET PASS
		} else if (isset($_POST['password']) && isset($_POST['confirm'])) {

			// Preconditions
			if($_POST['password'] != $_POST['confirm']){
				$this->error = "FAIL: password != confirmation";
			} else {

				// update the authentication
				$mAuthenticationBean = new MAuthenticationBean();
				$mAuthenticationBean->login =  $_SESSION['loginToUpdate'];
				$mAuthenticationBean->user = "MYMED_" . $_SESSION['loginToUpdate'];
				$mAuthenticationBean->password = hash('sha512', $_POST["password"]);

				$request = new Request("AuthenticationRequestHandler", UPDATE);
				$request->addArgument("authentication", json_encode($mAuthenticationBean));
				$request->addArgument("oldLogin", $_SESSION['loginToUpdate']);
				$request->addArgument("oldPassword", $_SESSION['passwordToUpate']);

				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);

				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				} else {
					$this->success = "votre mot de passe à bien été changé.";
				}
					
				$this->renderView("login");
			}
			unset($_SESSION['loginToUpdate']);
			unset($_SESSION['passwordToUpate']);
			$this->renderView("login");
		}

	}

}
?>