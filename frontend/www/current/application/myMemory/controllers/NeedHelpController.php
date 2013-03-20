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

class NeedHelpController extends AuthenticatedController {

	
	public function handleRequest(){
		
		parent::handleRequest();
		
		/*
		 * Set an emergency flag to true
		 */
		$_SESSION['emergency'] = true;
		
		if ( isset($_SESSION['emergency_level']))
		{
			$nbOfBuddies = count($_SESSION['ExtendedProfile']->callingList);
			
			// increment the id of the buddy to call
			$_SESSION['emergency_level'] += 1;
			
			// If we have gone through the whole list, reset
			if ($_SESSION['emergency_level'] >= ($nbOfBuddies-1)){
				
				$_SESSION['emergency_level'] = 0;
			}
		}
		else {
			// Fill the first buddy to call
			$_SESSION['emergency_level'] = 0;
			
		}
			
		$_SESSION['emergency_next_phonecall'] = $_SESSION['ExtendedProfile']->callingList[$_SESSION['emergency_level']]->phone;
		
		$this->renderView("needHelp");
		
	}
	
	

}
?>