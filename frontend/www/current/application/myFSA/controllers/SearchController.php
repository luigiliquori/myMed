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

/**
 *  This controller shows the search form and receives "search" queries.
 *  It renders the views "main" or "results".
 */
class SearchController extends AuthenticatedController {
	
	public function handleRequest() {
	
		parent::handleRequest();
			
		if(true) {
	
			// -- Search
			$this->search();	
			$this->renderView("search");
			
		} 
	}
	
	public function search() {
	
			// -- Search	
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();			
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		//if isset pred2 && pred3 it means someone used searching advanced
		if(isset($_POST['pred2']) && isset($_POST['pred3']) && (!empty($_POST['pred2']) || !empty($_POST['pred3']))){
			
			$obj->pred2 = $_POST['pred2'];
			$obj->pred3 = $_POST['pred3'];			
		} 
		
		//otherwise is displaying all publications
		else {
			
			$obj->pred1 = "FSApublication";
		}
	
	}
 	
}
?>