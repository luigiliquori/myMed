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

class MyEduSubscriptionBean extends GenericDataBean{
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	
	public $publisher;
	public $pubTitle;
	public $type;
	
	/**
	 * Some fields, not part of predicates, but wrapped in "_data"
	 * to be fetched by a "search" query (no need to "details").
	 * Register them in the contructor (3rd array)
	 */
	public $category;
	public $organization;
	public $locality;
	public $area;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $data1;
	public $data2;
	public $data3;
	
	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */)
	{
	parent::__construct(
	
			// Predicate attributes
	array(
			"pubTitle" => KEYWORD,
			"publisher"=> KEYWORD,
			"type"=> KEYWORD),
	
			// Data attributes
			array(
					"data1" => TEXT,
					"data2" => TEXT,
					"data3" => ENUM),
	
					// Wrapped attributes
					array("category","locality","organization","area"),
	
					$predicateStr);
	
	}
}