<?php

class SampleModel extends GenericDataBean {
	
	public $pred1;
	public $pred2;
	public $pred3;
	public $data1;
	public $data2;
	public $data3;
	public $wrapped1;
	public $wrapped2;
	
	// ---------------------------------------------------------------------
	// 	Constructor
	// ---------------------------------------------------------------------
	
	function __construct() {
		
		// Namespace
		$this->NAMESPACE = "SampleModel";
		
		// Declare predicate attributes
		$this->_predicatesDef = array(
				"pred1" => ENUM,
				"pred2" => GPS,
				"pred3" => DATE);
		
		// Declare data attributes
		$this->_dataDef = array(
				"data1" => KEYWORD,
				"data2" => TEXT,
				"data3" => DATE);
		
		// Declare wrapped attributes
		$this->_wrapDef = array("wrapped1", "wrapped2");
	}
	
}

?>