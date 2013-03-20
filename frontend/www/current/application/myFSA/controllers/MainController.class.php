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
<?

/**
 *  Main controller 
 *  
 */
class MainController extends AuthenticatedController {
	

	public function handleRequest() {
		
		parent::handleRequest();
			
		if(!isset($_SESSION['ExtendedProfile']))
			$this->fetchExtendedProfile();
	
		$this->renderView("main");
	}
	
	public /*void*/ function fetchExtendedProfile(){
	
		$result = ExtendedProfile::getExtendedProfile($this, $_SESSION['user']->id);
	
		if (!empty($result)){
			$_SESSION['ExtendedProfile'] = $result;
		}
	
	}
}
?>