<?php

/* OntologyID */
define('KEYWORD'	, 0);
define('GPS'		, 1);
define('ENUM'		, 2);
define('TEXT'		, 3);
define('PICTURE'	, 4);
define('VIDEO'		, 5);
define('AUDIO'		, 6);

/**
 *
 */
class Data {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	public /*String*/		$key;
	public /*byte[]*/		$value;
	public /*int*/			$ontologyID;
	
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*byte[]*/ $key, /*byte[]*/ $value, /*OntologyID*/ $ontologyID=TEXT) {
		$this->key = $key;
		$this->value = $value;
		$this->ontologyID = $ontologyID;
	}
}
?>
