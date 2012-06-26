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
 * Can be used to search, publish and get details in the mymed backend.
 *
 * The sub classes should define public attributes and describe it
 * in the contructor by defining the list of attributes and their corresponding types (OntologyID),
 * either in the list of 'predicates' or 'data'.
 *
 * @see OntologyBean
 * @author Raphael Jolivet
 */
include("OntologyBean.php");

abstract class GenericDataBean {

	// -- Public fields

	/** Date like dd-mm-yyyy - HH:MM:SS' or NULL. TODO: Check the format*/
	public $begin = null;
	public $end = null;

	/** User id of the publisher */
	public $publisherID = null;

	// -- Private fields

	/**
	 * This field corresponds to the user data field stored in the ApplicationController SCF.
	 * This field is obtained together with "begin" "end" and "publisherID" as a result of a search query,
	 * without requiring an additional call to the "details" API.
	 *
	 * The GenericDataBean uses this field to wrap/unwrap all the predicates and any aditional attribute
	 * registered in the "$_wrapDef" array.
	 *
	 * This is usefull to overcome a miss in the backend :
	 * The full detailed predicate is not returned by the "search" API :
	 * We only get the concatened predicate string.
	 *
	 * Therefore, by using the data field as a container, we can obtain
	 * all the predicates (and any additional attribute) in the result of the "search" API.
	 */
	private $_data = null;

	/**
	 * Acts as an ID for the object.
	 * If not set, the predicate string is computed from the current values of the predicate attributes.
	 * This value is automatically set if the object is fechted from a "find" query.
	 */
	private $_predicateStr;

	/** Array of attributeName => ontologyID. The values are retrieved from the corresponding atribute : $this->$key */
	private  $_predicatesDef;

	/** Array of attributeName => ontologyID. The values are retrieved from the corresponding atribute : $this->$key */
	private  $_dataDef;

	/** Array of attributes names to be wrapped / unwrapped into the "data" user field stored in the ApplicationController SCF */
	private  $_wrapDef;

	// ---------------------------------------------------------------------
	// 	Constructor
	// ---------------------------------------------------------------------

	/**
	 * Describe the list of attributes of the class, and their types (ontology id)
	 */
	public function __construct(
			array $predicatesDef /** Array of attributeName => ontologyId */,
			array $dataDef      /** Array of attributeName => ontologyId */,
			array $wrapDef = null /** Array of attribute names to be wrapped into "_data" in addition to predicates */,
			$predicateStr = null /** Optional: Predicate string (ID) */)
	{
		$this->_predicatesDef = $predicatesDef;
		$this->_dataDef = $dataDef;
		$this->_wrapDef = is_null($wrapDef) ? array() : $wrapDef;
		$this->_predicateStr = $predicateStr;
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
		foreach($this->_predicatesDef as $key => $ontologyID) {
			$value = $this->$key;
			
			if (!empty($value)) {
				array_push(
					$result,
						
					// Get the value of the corresponding attribute and create ontology bean
					new OntologyBean($key, $value, $ontologyID));
			}
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
		foreach($this->_dataDef as $key => $ontologyID) {
			array_push(
					$result,
						
					// Get the value of the coresponding attribute and create ontology bean
					new OntologyBean($key, $this->$key, $ontologyID));
		}

		// Add extra fields as ontologies
		if (!is_null($this->begin)) array_push($result, new OntologyBean("begin", $this->begin, DATE));
		if (!is_null($this->end))   array_push($result, new OntologyBean("end", $this->end, DATE));
		if (!is_null($this->_data))  array_push($result, new OntologyBean("data", $this->_data, TEXT));

		return $result;
	}

	/**
	 * Get the predicate as string : Concatenated
	 * @return Array of OntologyBeans
	 */
	public function getPredicateStr() {
		if (isset($this->_predicateStr)) {
			return $this->_predicateStr;
		} else {
			$predicate = "";
			foreach($this->getPredicates() as $ont) {
				$predicate .= $ont->key . $ont->value;
			}
			return $predicate;
		}
	}

	// ---------------------------------------------------------------------
	// Private methods
	// ---------------------------------------------------------------------

	/**
	 * Wrap all the predicates (and additional attributes listed in $_wrapDef) into a JSON within the "data" field,
	 * before publishing.
	 */
	private function wrapData() {

		// Object to wrap
		$obj = new stdClass();

		// Store predicates and extra attributes to wrap in the object
		$attrs = array_merge(array_keys($this->_predicatesDef), $this->_wrapDef);
		debug(print_r($attrs, true));
		foreach($attrs as $attr) {
			$obj->$attr = $this->$attr;
		}

		// Transform to json and store it into "_data"
		$this->_data = json_encode($obj);

		
	}

	/**
	 * Unwrap all the predicates (and additional attributes listed into $_wrapDef) from a JSON into the "data" field,
	 * used after a "search" query.
	 */
	private function unwrapData() {

		// Unwrapp json
		$obj = json_decode($this->_data);

		// Put data into object attributes
		$attrs = array_merge(array_keys($this->_predicatesDef), $this->_wrapDef);
		foreach($attrs as $attr) {
			$this->$attr = $obj->$attr;
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

		debug("Predicate STR:" . $this->getPredicateStr());
		
		// Create a find request
		$fr = new FindRequest(
				null,
				$this->getPredicateStr(),
				null);

		// Get a list of result
		$items = $fr->send();

		$className = get_class($this);
		$res = array();

		// Loop on results
		foreach($items as $item) {
				
			// Create new instance of same class
			$obj = new $className();
				
			// Copy the search query
			// TODO Get it from the backend
			foreach($this->_predicatesDef as $key => $ontID) {
				$obj->$key = $this->$key;
			}
				
			// Fill with the results
			$obj->begin = $item->begin;
			$obj->end   = $item->end;
			$obj->publisherID = $item->publisherID;
			$obj->_data  = $item->data;
			$obj->_predicateStr = $item->predicate;
				
			// Unwrapp "data"
			$obj->unwrapData();
				
			array_push($res, $obj);
		}

		return $res;
	}

	/**
	 * Publish the content of this bean to the backend.
	 */
	public function publish() {
		$this->wrapData();
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
				null,
				$this->getPredicateStr(),
				$this->publisherID);

		// Get a list of result
		$res = $fr->send();

		// Loop on details
		foreach($res as $item) {
				
			$key = $item->key;
				
			// Check that the details are registered in the "model"
			if ($key == "data") {
				$key = "_data"; // Put "data" into "_data"
			} else if ($key == "begin" || $key == "end") {
				// Don' t check these, there are two custom attributes not listed in the "definition"
			} else if (!key_exists($key, $this->_dataDef)) {
				$classname = get_class($this);
				throw new Exception(
						"Key '$key' returned as details by the backend, but not defined in ${classname}->dataDef");
		}
			
		// Set it
		$this->$key = $item->value;
			
	} // End of loop on details

	// Unwrap "_data"
	$this->unwrapData();

}

}
?>
