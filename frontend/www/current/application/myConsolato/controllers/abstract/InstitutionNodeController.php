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
 * Abstract controller for institution nodes (common to article and category), which 
 * handles parent hierarchy 
 */
abstract class InstitutionNodeController extends GenericCRUDController {
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	public function __construct($classname, $dataname) {
		parent::__construct($classname, $dataname); // Name of the bean for the view  
	}
	
	// ---------------------------------------------------------------------
	// Actions
	// ---------------------------------------------------------------------
	
	/** Add parentID to the form, as hidden field */
	public function createHook() {
		$this->item->parentID = $_REQUEST['parentID'];
	}
	
	/** After create, redirect to parent category instead of created publication */
	public function doCreateHook() {
		$this->success = $this->item->title . " " . _("a été publié");
		// Show the parent category
		$this->forwardTo(
				"institutionCategory:show", 
				array("id" => $this->item->parentID));
	}
	
	/** After delete, redirect to parent node */
	public function delete($id) {
		parent::delete($id);

        // Show success message
		$this->success = $this->item->title . " " . _("a été supprimé");
		
		// Show the parent category
		$this->forwardTo(
				"institutionCategory:show", 
				array("id" => $this->item->parentID));		
	}
	
	
}