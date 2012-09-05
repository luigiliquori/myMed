<?php

define('REPUTATION_PRED' , 'LAUNCHPAD_REP');

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class StoreController extends MainController {

	public /*void*/ function handleRequest() {

		if(isset($_REQUEST['applicationStore'])) {
				
			// update the application status
			if(isset($_REQUEST['status'])) {
				// update status
				$extentedProfile = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
				$appList = $extentedProfile->applicationList;
				$newAppList = array();
				if($_REQUEST['status'] == "off") {
					foreach ($appList as $app) {
						if($app != $_REQUEST['applicationStore']) {
							array_push($newAppList, $app);
						}
					}
					$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant désactivée");
				} else if ($_REQUEST['status'] == "on") {
					foreach ($appList as $app) {
						array_push($newAppList, $app);
					}
					array_push($newAppList, $_REQUEST['applicationStore']);
					$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant activée");
				}
				
				$extentedProfile = new ExtendedProfile($_SESSION['user']->id, $newAppList);
				$extentedProfile->storeProfile($this);
					
				// set the status of the applications
				$this->resetApplicationStatus();
				foreach ($extentedProfile->applicationList as $app){
					$this->applicationStatus[$app] = "on";
				}
			}

			// update the reputation of the application
			if(isset($_REQUEST['reputation'])) {
				// Get the reputation of the user in each the application
				$request = new Request("InteractionRequestHandler", UPDATE);
				$request->addArgument("application",  APPLICATION_NAME);
				$request->addArgument("producer",  $_REQUEST['applicationStore']);					// Reputation of data
				$request->addArgument("consumer",  $_SESSION['user']->id);
				$request->addArgument("start",  time());
				$request->addArgument("end",  time());
				$request->addArgument("predicate",  REPUTATION_PRED);
				$request->addArgument("feedback",  $_REQUEST['reputation']);

				try {
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
						
					if($responseObject->status != 200) {
						$this->currentErrorMess = "Vous n'avez pas le droit de voter plus d'une fois.";
					} else {
						$this->currentSuccessMess = "Merci de votre contribution!";
					}
				} catch (Exception $e) {
					$this->currentErrorMess = "Une erreur interne est survenue, veuillez réessayer plus tard...";
				}
			}
				
		}

		parent::handleRequest();

	}

}
?>
