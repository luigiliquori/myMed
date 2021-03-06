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
class MyEduPublication extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = 'myEduPublication';
	public $area;
	public $category;
	public $locality;
	public $organization;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $title;
	public $text;
	public $maxappliers;			// Max appliers to a course
	public $currentappliers;		// Current appliers to a course
	
	public $pred1;
	public $pred2;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"publisher"	=> KEYWORD,
						"type"	=> KEYWORD,
						"category" => KEYWORD,
						"locality" => KEYWORD,
						"organization" => KEYWORD,
						"area" => KEYWORD,
						"title" => KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS),
				
				// Data attributes 
				array("text" => TEXT, 
					  "maxappliers" => TEXT,
					  "currentappliers" => TEXT),
				
				// Wrapped attributes
				array("title", "end"),
				
				$predicateStr);
		
	}
}

?>