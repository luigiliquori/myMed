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
 * Retrive the list of a user candidatures and render
 * MyCandidatureView 
 */
class CandidatureController extends AuthenticatedController {
	
	/**
	 * This will create a temporary Profile with the informations submited by POST.
	 * @see IRequestHandler::handleRequest()
	 */
	public /*String*/ function handleRequest() { 

		parent::handleRequest();
		
		switch ($_GET['method']){
			case 'show_all_candidatures':
				$this->search_all_applies();
				break;
			case 'show_candidatures':
				$this->search_applies();
				break;
		}
	}
	
	private function search_all_applies(){
		$search_applies = new Apply();
		$this->result_apply = $search_applies->find();

		$this->renderView("AllCandidatures");
	}
	
	private function search_applies(){
		$search_applies = new Apply();
		$search_applies->publisher=$_SESSION['user']->id;
		$this->result = $search_applies->find();
	
		$this->renderView("MyCandidature");
	}
}

?>