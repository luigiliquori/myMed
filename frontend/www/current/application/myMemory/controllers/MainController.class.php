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
<? 
class MainController extends AuthenticatedController {
	
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		if (!isset($_SESSION['autocall_active']))
			$_SESSION['autocall_active'] = false;
		
		
		/*
		 * Detect if the user is using a mobile device.
		 * This application will have different dehaviours for mobile.
		 */
		$m = new Mobile_Detect();
		$_SESSION['isMobile'] = $m->isMobile();
		//$_SESSION['isMobile'] = true;
		
		/*
		 * Try to get the User ExtendedProfile if it exist
		 * ExtendedProfile stored in the $_SESSION while using the app
		 */
		if( !isset($_SESSION['ExtendedProfile'])){
			$this->fetchExtendedProfile();

		}
		else
			$this->renderView("Main");
		
	}
	
	/**
	 * Fetch the user extendedProfile by using the static method getExtendedProfile of the class ExtendedProfile.
	 * In case the profile is found, it is stored in$_SESSION['ExtendedProfile'] and the Main view is called.
	 * Else, the ExtendedProfileNeeded viex is called.
	 * @param implicit : use the current User Id stored in the session.
	 * @see ExtendedProfile::getExtendedProfile()
	 */
	public /*void*/ function fetchExtendedProfile(){
		
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
		
		if (empty($result)){
			$this->error = "";
			$this->renderView("ExtendedProfileNeeded");
		}
		else {
				
			$_SESSION['ExtendedProfile'] = $result;
			$this->success = "";
			$this->renderView("Main");
		}

	}
	
	
}
?>