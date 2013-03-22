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
 * Publication  
 * 
 */
class myEuroCINPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type; // MyEuroCinPublication
	public $data; // Title
	public $Nazione; // City
	public $expire_date;
	public $Lingua;
	public $Arte_Cultura;
	public $Natura;
	public $Tradizioni;
	public $Enogastronomia;
	public $Benessere;
	public $Storia;
	public $Religione;
	public $Escursioni_Sport;
	
	
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $text;
	public $validated;
	
	
	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"type"	=> KEYWORD,
						"publisher"	=> KEYWORD,
						"begin"	=> KEYWORD,
						"expire_date"	=> KEYWORD,
						"data" => KEYWORD,
						"Nazione" => KEYWORD,
						"Lingua" => KEYWORD,
						"Arte_Cultura" => KEYWORD,
						"Natura" => KEYWORD,
						"Tradizioni" => KEYWORD,
						"Enogastronomia" => KEYWORD,
						"Benessere" => KEYWORD,
						"Storia" => KEYWORD,
						"Religione" => KEYWORD,
						"Escursioni_Sport" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => KEYWORD
						),
				
				// Data attributes 
				array("text" => TEXT, 
					  "validated" => TEXT),
				
				// Wrapped attributes
				array("end"),
				
				$predicateStr);
		
	}
	
	/** Return the title of the publication */
	public function getTitle() {
		return $this->_data;
	}
}

?>