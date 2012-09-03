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
class MAbstractDataObject {
	
	/** Array of "attribute-name" => ONTOLOGY_ID */  
	private $_attributes;
	
	/** Constructor : should be overriden and provide the description of attributes as array of "attribute-name" => ONTOLOGY_ID */
	public function __construct($attributes) {
		$this->_attributes = $attributes;
	}
	
	/** Returns a list of data beans */
	public function toDataBeans() {
		
		$result = array();
		
		foreach	($this->_attributes as $key => $ontologyID) {
			
			// Create a data bean
			$bean = new MDataBean($key, $this->$key, $ontologyID);

			// Add to result
			array_push($result, $bean);
		}
		
		return $result;
	}
	
	/** Parse a list of data beans and set the corresponding attributes */
	public function fromDataBeans($dataBeans) {
		// TODO
	}
}

?>
