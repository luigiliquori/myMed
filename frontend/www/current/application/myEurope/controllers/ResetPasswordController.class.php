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
				$this->success = "Un mail vient de vous être envoyé!";
			}
			include( MYMED_ROOT . "/application/myMed/views/LoginView.php");
			exit();
		}

	}

}
?>