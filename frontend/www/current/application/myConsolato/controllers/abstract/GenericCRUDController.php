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
/** 
 * This class provides a generic CRUD(Create Read Update Delete) controller 
 * for publication object.
 * It provides the following method (sub-actions) :
 * * create : Show the creation form
 * * doCreate : Process to the creation and show back the created object
 * * edit : Show the edit form
 * * doEdit : Process update a and back the edited object
 * * show : Show the object
 *
 * The class of the object should provide a getByID static method.
 * The views should be called :
 * * Create<ClassName>View
 * * Show<ClassName>View
 * * Edit<ClassName>View
 * 
 * Some hook are created that you can override, just before the view is rendered.
 * * showHook()
 * * editHook()
 * * createHook()
 * 
 */
class GenericCRUDController extends GuestOrUserController {

	// ---------------------------------------------------------------------
	// Attributes
	// ---------------------------------------------------------------------
	
	/** Name of the class to handle */
	protected $className;
	protected $dataName;
	
	// ---------------------------------------------------------------------
	// Constructor
	// ---------------------------------------------------------------------
	
	/**
	 *  @param $classname Name of the class of the object to handle
	 *  @param $dataName name of the attribute set into the controller and passed to the view
	 *         The data object is also available via $this->item.
	 */
	public function __construct($className, $dataName) {
		parent::__construct();
		$this->className = $className;
		$this->dataName = $dataName;
	}
	
	// ---------------------------------------------------------------------
	// Methods (sub-actions)
	// ---------------------------------------------------------------------
	
	/** Show one object */
	function show($id) {

		$dataName = $this->dataName;
		$className = $this->className;
		
		// Get the object and set it 
		$this->$dataName = $className::getByID($id);
		$this->item= $this->$dataName;

		$this->showHook();
		
		// Show the form
		$this->renderView("Show" . $className);
	}
	
	/** Hook before the view is rendered : May be overridden */
	protected function showHook() {
		// Nothing yet
	}

	/** Show the form for new article */
	function create() {
		$dataName = $this->dataName;
		$className = $this->className;
		
		// Create empty object
		$this->$dataName = new $className();
		$this->item= $this->$dataName;

		$this->createHook();
			
		// Show the form
		$this->renderView("Create" . $className);
	}
	
	/** Hook before the view is rendered : May be overridden */
	protected function createHook() {
		// Nothing yet
	}
	
	/** Actually create a publication */
	function doCreate() {
	
		$dataName = $this->dataName;
		$className = $this->className;
	
		$this->item = new $className();
		$this->item->id = uniqid();
		$this->item->populateFromRequest();
		$this->item->publish();
		
		$this->doCreateHook();
		
		$this->show($pub->id);
	}
	
	/** Hook before the render of the view : May be overridden */
	protected function doCreateHook() {
		// Nothing yet
	}
	
	/** Show the edit form */
	function edit($id) {
		$dataName = $this->dataName;
		$className = $this->className;
		
		// Get the item
		$this->item = $className::getByID($id);
		$this->$dataName = $this->item;
		
		// Show the view 
		$this->renderView("Edit" . $className);
	}
	
	function doEdit($id) {
		
		$dataName = $this->dataName;
		$className = $this->className;
		
		// Get the item
		$this->item = $className::getByID($id);
		$this->$dataName = $this->item;	
		
		// Fill fields
		$this->item->populateFromRequest();
		$this->item->publish();
		
		// Show the view
		$this->renderView("Show" . $className);
	}
	
	/** Delete an item : Should be overriden by sub-class to call a specific view after deletion */
	function delete($id) {
		
		$className = $this->className;
		
		// Get the item
		$this->item = $className::getByID($id);
		$this->item->delete();
		
	}


}