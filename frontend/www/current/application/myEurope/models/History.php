<?php

class History
{

	/*
	 * Attributes
	 */

	public $id;
	public $contentRep;
	public $authorRep;
	
	
	public function __construct( $id, $contentRep=null, $authorRep=null ){
		
		$this->id = $id;
		$this->contentRep = $contentRep;
		$this->authorRep = $authorRep;

	}


	
}