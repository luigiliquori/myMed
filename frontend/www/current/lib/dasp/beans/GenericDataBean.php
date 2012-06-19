<?php
/*
 * Copyright 2012 INRIA
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

/**
 * Represents a published object as a standard PHP objet.  
 * Can be used to search, pulish and get details in the mymed backend.
 * 
 * The sub classes should define public attributes and describe it 
 * in the contructor by defining the list of attributes and their correspoding types (OntologyID), 
 * either in the list of 'predicates' or 'data'. 
 * 
 * @see OntologyBean
 * @author Raphael Jolivet
 */
include("OntologyBean.php");

abstract class GenericDataBean {

	/** Date like dd-mm-yyyy - HH:MM:SS' or NULL. TODO: Check the format*/
	public $begin = null;
	public $end = null;
	
	/** 
	 * Free text, stored in the ApplicationController and retrieved with FindRequestHandler withoput 
	 * further call to "Details" 
	 */
	public $data = null;
	
	/** User id of the publisher */
	public $publisherID = null;
	
	/** Array of attributeName => ontologyID. The values are retrieved from the corresponding atribute : $this->$key */
	private  $predicatesDef;
	
	/** Array of attributeName => ontologyID. The values are retrieved from the corresponding atribute : $this->$key */
	private  $datasDef;

	// ---------------------------------------------------------------------
	// 	Constructor
	// ---------------------------------------------------------------------
		
	/**
	 * Describe the list of attributes of the class, and their types (ontology id) 
	 */
	public function __construct(
		array $predicatesDef /** Array of attributeName => ontologyId */, 
		array $datasDef      /** Array of attributeName => ontologyId */)
	{
		$this->predicatesDef = $predicatesDef;
		$this->datasDef = $datasDef;
	}
	
	// ---------------------------------------------------------------------
	// 	Getters
	// ---------------------------------------------------------------------
	
	
	/**
	 * Get the array of predicates
	 * @return Array of OntologyBeans
	 */
	public /*OntologyBean[]*/ function getPredicates() {

		$result = array();

		// Loop on registered attributes
		foreach($this->predicatesDef as $key => $ontologyID) {
			array_push(
					$result,
					
					// Get the value of the coresponding attribute and create ontology bean  
					new OntologyBean($key, $this->$key, $ontologyID));
		}
		return $result;
	}

	/**
	 * Get the array of datas
	 * Include the special attributes "begin" "end" and "data" if available
	 * @return Array of OntologyBeans
	 */
	public /*OntologyBean[]*/ function getDatas(){
		
		$result = array();
		
		// Loop on registered attributes
		foreach($this->datasDef as $key => $ontologyID) {
			array_push(
					$result,
					
					// Get the value of the coresponding attribute and create ontology bean
					new OntologyBean($key, $this->$key, $ontologyID));
		}
		
		// Add extra fields as ontologies
		if (!is_null($this->begin)) array_push($result, new OntologyBean("begin", $this->begin, DATE));
		if (!is_null($this->end))   array_push($result, new OntologyBean("end", $this->end, DATE));
		if (!is_null($this->data))  array_push($result, new OntologyBean("data", $this->data, TEXT));
		
		return $result;
	}
	
	/**
	 * Get the predicate as string : Concatenated
	 * @return Array of OntologyBeans
	 */
	public function getPredicateStr() {
		$predicate = "";
		foreach($this->getPredicates() as $ont) {
			return $predicate .= $ont->key . $ont->value;
		}
	}

	// ---------------------------------------------------------------------
	// Communication with backend
	// ---------------------------------------------------------------------
	
	/** 
	 * Use the current instance as a search query and returns the list of matching elements as
	 * an array of instances of the same class.
	 */
	public /* <CurrentClass>[]  */ function find() {
		
		// Create a find request
		$fr = new FindRequest(
				$null, 
				$this->getPredicateStr(),
				null);
		
		// Get a list of result
		$res = $fr->send();
		
		$className = get_class($this);
		$res = array();
		
		// Loop on results
		foreach($res as $item) {
			
			// Create new instance of same class
			$obj = new $className();
			
			// Copy the search query
			// TODO Get it from the backend
			foreach($this->predicatesDef as $key => $ontID) {
				$obj->$key = $this->$key;
			}
			
			// Fill with the results
			$obj->begin = $item->begin;
			$obj->end   = $item->end;
			$obj->data  = $item->data;
			$obj->publisherID = $item->publisherID;
			
			array_push($res, $obj);
		}
		
		return $res;
	}
	
	/**
	 * Publish the content of this bean to the backend.
	 */
	public function publish() {
		$pr = new PublishRequest(null, $this, $this->publisherID);
		$pr->send();
	}
	
	/**
	 *  Get the extra data from the DataList SCF
	 *  and fills the correspoding attributes with it. 
	 */
	public function getDetails() {
		
		if (empty($this->publisherID)) {
			throw new Exception("Cannot get details of a data bean without the publisher ID");
		}
		
		// Create a find request
		$fr = new FindRequest(
				$null,
				$this->getPredicateStr(),
				$this->publisherID);
		
		// Get a list of result
		$res = $fr->send();
		
		// Loop on details
		foreach($res->details as $item) {
			
			$key = $item->key;
			
			// Check that the details are registered in the "model"
			if (!key_exists($key, $this->datasDef)) {
				$classname = get_class($this);
				throw new Exception(
						"Key '$key' returned as details by the backend, but not defined in ${classname}->datasDef");
			}
			
			// Set it
			$this->$key = $item->val;
			
		} // End of loop on details
		
	}

}
?>
