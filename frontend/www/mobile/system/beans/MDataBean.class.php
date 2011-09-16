<?php

/* OntologyID (predicate) */
define('KEYWORD'	, 0);
define('GPS'		, 1);
define('ENUM'		, 2);
define('DATE'		, 3);
/* OntologyID (others) */
define('TEXT'		, 4);
define('PICTURE'	, 5);
define('VIDEO'		, 6);
define('AUDIO'		, 7);

/**
 * Represente a Data in the myMed model
 */
class MDataBean {
	
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
