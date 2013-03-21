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