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


class ReputationController extends AbstractController
{
	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Reputation") {
				//$this->storeReputation();
			
				$debugtxt  =  "<pre>CONTROLLLLLEEEEEEEEEEEEEERRR";
				$debugtxt  .= var_export($_REQUEST['rate'], TRUE);
				$debugtxt  .= var_export($_SESSION['user'], TRUE);
				$debugtxt .= "</pre>";
				$debugtxt  .= var_export(htmlspecialchars($_GET["action"]), TRUE);
				debug($debugtxt);
	
		}
		else {
	
			// -- Show the form
		}
	}
	
	public /*void*/ function storeReputation(){
		
			
			$rep = new Reputation('myFSA',$_SESSION['user']);
			
			$rep->storeReputation();
			
	}
	
	public /*void*/ function getReputation(){
		
		//ExtendedProfile::getExtendedProfile($_SESSION['user']);
	}
	
	public /*void*/ function showProfile(){

		//$this->renderView("ExtendedProfileDisplay");
	}

}