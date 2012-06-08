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
 * Represente an ontology in the myMed model
 */
class OntologyBean {

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
