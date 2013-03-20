<!--
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
 -->
<?php

/**
 *
 * Enter description here ...
 * @author lvanni
 *
 */
class SubscribeController extends AuthenticatedController {
	
	public /*void*/ function handleRequest() {
		
		parent::handleRequest();
		
		// Read subscription of the user
		if(isset($_POST['method']) && $_POST['method'] = "Get Subscription") {

			// Get the reputation of the user in each the application
			$request = new Request("SubscribeRequestHandler", READ);
			$request->addArgument("application",  APPLICATION_NAME);
			$request->addArgument("userID",  $_SESSION['user']->id);
			
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			
			if($responseObject->status != 200) {
				$this->subscription = $responsejSon;
			} else {
				$this->subscription = $responsejSon;
			}
			
		}
		
		$this->renderView("profile");
		
	}

}
?>