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


/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
define('REPUTATION_PRED' , 'LAUNCHPAD_REP');

define('EXTENDED_PROFILE_PREFIX' , 'extended_profile_');
define('STORE_PREFIX' , 'store_');

class StoreController extends AbstractController { // AuthenticatedController {

	
	public /*void*/ function handleRequest() {
		
		parent::handleRequest();
		
		/* Guest access provided */
		/*if (!(isset($_SESSION['user']))) {
			$id = rand(100000, 999999);
			$user = (object) array('id'=>'MYMED_'.$id, 'name'=>'user'.$id);
			$_SESSION['user'] = insertUser($user, null, true);
			$_SESSION['acl'] = array('defaultMethod', 'read');
			$_SESSION['user']->is_guest = 1;
		}*/
		
		if(isset($_REQUEST['applicationStore'])) {
				
			// update the application status
			if(isset($_REQUEST['status'])) {
				// update status
				//$extentedProfile = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
				//$appList = $extentedProfile->applicationList;
				//$newAppList = array();
				
				$_SESSION['applicationList'][$_REQUEST["applicationStore"]] = $_REQUEST['status'];
				$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant ".$_REQUEST['status']);
				
				$extentedProfile = new ExtendedProfile($this, $_SESSION['user']->id, $_SESSION['applicationList']);
				try{
					$extentedProfile->storeFavoriteApps();
				}catch(Exception $e){
					$this->setError(_("Echec de sauvegarde ").$e);
				}
				
				/*if($_REQUEST['status'] == "off") {
					$_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "off";
					foreach ($appList as $app) {
						if($app != $_REQUEST['applicationStore']) {
							array_push($newAppList, $app);
						}
					}
					$this->setSuccess("L'application " . $_REQUEST['applicationStore'] . " est maintenant désactivée");
				} else if ($_REQUEST['status'] == "on") {
					$_SESSION['applicationList'][$_REQUEST["applicationStore"]] == "on";
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
				*/
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
				$request->addArgument("feedback",  $_REQUEST['reputation']/10);

				try {
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);

					if($responseObject->status != 200) {
						$this->setError("Vous n'avez pas le droit de voter plus d'une fois");
					} else {
						$this->setSuccess("Merci de votre contribution");
					}
				} catch (Exception $e) {
					$this->setError("Une erreur interne est survenue, veuillez réessayer plus tard...");
				}
				
				// Update reputation values
				$this->updateAllAppsReputation();
			}	
			$this->renderView("storeSub");
		}
		$this->renderView("store");
		
	}

	
	function updateAllAppsReputation() {
		
		foreach($_SESSION['applicationList'] as $app => $status){
				
			// Get the reputation of the user in each application
			$request = new Request("ReputationRequestHandler", READ);
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("producer",  $app);					// Reputation of data
			$request->addArgument("consumer",  $_SESSION['user']->id);
		
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
		
			if (isset($responseObject->data->reputation)) {
				$i=0;
				$value = json_decode($responseObject->data->reputation);
				$_SESSION['reputation'][EXTENDED_PROFILE_PREFIX . $app] = $value * 100;
			} else {
				$_SESSION['reputation'][EXTENDED_PROFILE_PREFIX . $app] = 100;
			}
				
			// Get the reputation of the application
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("producer",  $app);			// Reputation of data
			$request->addArgument("consumer",  $_SESSION['user']->id);
				
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if (isset($responseObject->data->reputation)) {
				$value = json_decode($responseObject->data->reputation);
				$_SESSION['reputation'][STORE_PREFIX . $app] = $value * 100;
			} else {
				$_SESSION['reputation'][STORE_PREFIX . $app] = 100;
			}
		}
	}
}
?>
