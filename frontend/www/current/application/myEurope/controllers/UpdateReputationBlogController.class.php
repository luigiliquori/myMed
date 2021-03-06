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
class UpdateReputationBlogController extends BlogDetailsController {
	
	public /*void*/ function handleRequest() {
		
		
		// The reputation in BlogDetailsView ranges from 1 to 5
		// while the system needs values from 1 to 10
		$_GET['reputation'] = $_GET['reputation']*2;
		
		// update the reputation of the application
		if(isset($_GET['reputation'])) {

			if($_SESSION['user']->id == $_GET['author']){
				$this->error = _("You can't rate your own publication!");
			}else{

				// Get the reputation of the user in each the application
				$request = new Request("InteractionRequestHandler", UPDATE);
				$request->addArgument("application",  APPLICATION_NAME);
				if(isset($_GET['isData'])){
					$request->addArgument("producer", $_GET['predicate'].$_GET['author']);
				} else {
					$request->addArgument("producer",  $_GET['author']);	
				}				
				$request->addArgument("consumer",  $_SESSION['user']->id);
				$request->addArgument("start",  time());
				$request->addArgument("end",  time());
				$request->addArgument("predicate",  $_GET['predicate']);
				$request->addArgument("feedback",  "0.".$_GET['reputation']);

// 				try {
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				} else {
					$this->success = "Merci de votre contribution";
				}
// 				} catch (Exception $e) {
// 					$this->error = "Une erreur interne est survenue, veuillez réessayer plus tard...";
// 				}
			}
		}	
		
		parent::handleRequest();
		
	}

}
?>
