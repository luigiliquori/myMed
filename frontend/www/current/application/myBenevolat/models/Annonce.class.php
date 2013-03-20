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

/** Profile for an association */
class Annonce extends GenericDataBean {
	
	// ------------------------------------------------
	// Attributes
	// ------------------------------------------------
	
	/** Key: Annonce ID */
	public $id;
	public $type = "annonce";
	public $pred1;
	public $pred2;
	
	public $competences;
	public $typeMission;
	public $quartier;
	
	public $promue;
	public $validated;
	
	/** Data */
	public $title;
	public $text;
	
	// ------------------------------------------------
	// Constructor
	// ------------------------------------------------
	
	public function __construct($predicateStr = null){
		parent::__construct(
			// predicatesDef
			array(
				"publisher" => KEYWORD,
				"type"	=> KEYWORD,
				"id" => KEYWORD,
				"title" => KEYWORD,
				"pred1" => KEYWORD,
				"pred2" => GPS,
				"competences" => ENUM,
				"typeMission" => ENUM,
				"quartier" => ENUM),
		
			// dataDef
			array("text" => TEXT, "validated" => TEXT),
				
			// wrapDef
			array("promue", "begin", "end"),
			
			$predicateStr);
		
	}
}
?>
