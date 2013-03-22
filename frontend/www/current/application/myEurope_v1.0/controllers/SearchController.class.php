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

class SearchController extends ExtendedProfileRequired {

	function defaultMethod() {
		
		parent::handleRequest();
		
		$this->part = new Partnership();
		$this->part->setIndex($_GET);
		
		$mapper = new DataMapper;

		try {
			$this->result = $mapper->findByPredicate($this->part);
		} catch (Exception $e) {
			$this->result = array();
		}
		
		$this->suggestions = array();
		
		function addvaluelashes($o){
			$o->value = addslashes($o->value);
			return $o;
		}
		
		$this->part->index = array_map("addvaluelashes", $this->part->index); //for ajax subscribe
		
		$this->title = "";
		array_walk($this->part->index, array($this, "getValues"));
		if (empty($this->title)){
			$this->title = "all partnerships";
		}

		// Render the view			
		$this->renderView("Results");
		
	}
	
	function getValues($o){
		if (!empty($o->value)){
			$this->title .= str_replace('|', '+', $o->value).' ';
		}
	}
	
}
?>