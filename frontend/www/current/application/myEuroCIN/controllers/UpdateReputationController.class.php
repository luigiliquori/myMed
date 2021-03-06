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
class UpdateReputationController extends DetailsController {
	
	public /*void*/ function handleRequest() {
		
		// update the reputation of the application
		if(isset($_GET['reputation'])) {

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
			$request->addArgument("feedback",  $_GET['reputation']/10);

// 			try {
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
				
				if($responseObject->status != 200) {
					$this->error = $responseObject->description;
				} else {
					$this->success = _("Thank you for your contribution!");
				}
				$this->redirectTo("?action=details&predicate=".$_GET['predicate']."&author=".$_GET['author']);
		}	
		
		parent::handleRequest();
		
	}

}
?>
