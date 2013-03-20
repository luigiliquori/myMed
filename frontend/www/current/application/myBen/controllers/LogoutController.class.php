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

// TODO: Should be a common controller in /system/controllers/
class LogoutController extends AbstractController {

	/**
	 * Logout an User by destroying the frontend AND backend session
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest() {
	
			debug("Logout !");
		
			// DELETE BACKEND SESSION
			$request = new Requestv2("v2/SessionRequestHandler", DELETE);
			$request->addArgument("accessToken", $_SESSION['user']->session);
			$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			}
				
			// DELETE FRONTEND SESSION
			session_destroy();
			
			// Redirect to login
			$this->redirectTo("listAnnonces");	
	}

}
?>
