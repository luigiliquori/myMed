<!--
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
 -->
<?

/** 
 * Comments of Applies
 * 
 */
class Apply extends GenericDataBean {
	
	/** Some predicates .
	 * Register them in the contructor with appropriate ontologyID */
	public $type = "apply";
	public $pred1;
	public $pred2;
	public $idAnnonce;
	public $author;
	
	/** Some data (got after a "details" query).
	 *  Register them in the contructor with appropriate ontologyID */
	public $accepted;
	
	public $title;

	/** Register the attributes either as predicates / data fields */
	public function __construct(
			$predicateStr = null /** Optional: Predicate string (=ID) */) 
	{
		parent::__construct(
				
				// Predicate attributes
				array(
						"publisher"	=> KEYWORD, // student ID
						"type"	=> KEYWORD,
						"pred1" => KEYWORD,
						"pred2" => GPS,
						"idAnnonce" => KEYWORD, // id of the publication
						"author" => KEYWORD), // publication author ID
				
				// Data attributes 
				array("text" => TEXT),
				
				// Wrapped attributes
				array("accepted", "title"),
				
				$predicateStr);
		
	}
}

?>