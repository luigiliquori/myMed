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

define("ASS_ALL", "all");
define("ASS_INVALID", "invalid");

class ListAssociationsController extends ProfileAssociationRequired {
	
	/** @var ProfileAssociation[] */
	public $associations = array();
	public $filter = ASS_ALL;
	
	/** Processed when argument "method" is missing */
	function defaultMethod() {
		
		// Set the filter 
		if (in_request("filter")) {
			$this->filter = $_REQUEST['filter'];
		}
		
		// Query
		$query = new ProfileAssociation();
		
		// Build a query
		if ($this->filter == ASS_ALL) {

			$query->valid = "true";
			$this->associations = $query->find();
		}
		
		// XXX Hack : search valid and not valid associations to have it all
		$query->valid = "false";
		$this->associations = array_merge($this->associations, $query->find());
		
		// The view
		$this->renderView("listAssociations");
		
	}
}
?>