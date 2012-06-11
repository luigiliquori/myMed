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



/**
 * Represent a DataBean in the myMed model
 * A databean is composed of a predicate and datas.
 * A predicate is composed of 1 to 4 non null ontologies of a certain Type (KEYWORD, GPS, ENUM or DATE)
 * 
 * @see OntologyBean
 * @author David Da silva
 */
class DataBean {

	private /*OntologyBean[]*/ $predicates;
	private /*OntologyBean[]*/	$datas;


	/**
	 * This constructor will force the predicate to be valid by checking the number of ontologies in it and their type.
	 */
	public function __construct(/*OntologyBean[]*/ array $predicates, /*OntologyBean[]*/ array  $datas) {
		
		/*
		 * Verifications on the predicates
		 */
		
		// At least 1 predicate and no more than 4 predicates
		if ( count($predicates) < 1 || count($predicate) >4  )
			throw new Exception("Number of predicates should be between 1 and 4");
		
		// Check the validity of each ontology for predicate
		foreach ($predicates as $p)
		{
			if ( !$this->isValidPredicate($p))
				throw new Exception("Ontology is not valid for predicate");
		}
		
		$this->predicates = $predicates;
		$this->datas = $datas;
		
	}
	
	/**
	 * 
	 * Check if an ontology is valid as a predicate.
	 * To be valid it should :
	 * - match the types allowed
	 * - not be empty
	 * @param OntologyBean $ontology
	 * @return boolean
	 */
	public /*Boolean*/ function isValidPredicate(OntologyBean $ontology) {
			
		// check the type of the ontology
		if ($ontology->ontologyID > 3) // Only type 0, 1, 2 and 3 allowed
			return false;
		else {
			// check the value
			if ( $ontology->value == "" )
				return false;
			else
				return true;
		}
	}
	
	/**
	 * Add an Ontology to the array of predicate IF
	 *  - there is space left
	 *  - Ontology is valid as in isValidPredicate()
	 * @param OntologyBean
	 */
	public /*void*/ function pushPredicate(OntologyBean $ontology){
		
		// check the current size of the array of predicates
		if (count($this->predicates) < 4)
			throw new Exception("Predicate cannot be composed of more than 4 ontologies");
		else {
			// check the validity of ontology
			if ($this->isValidPredicate($ontology))
				array_push($this->predicates, $ontology);
			else 
				throw new Exception("Ontology is not valid for predicate");
		}
	}
	
	/**
	* Add an Ontology to the array of datas, no requirements
	* @param OntologyBean
	*/
	public /*void*/ function pushData(OntologyBean $ontology){
	
		array_push($this->datas, $ontology);
	}
	
	
	
	/**
	 * 
	 * Get the array of predicates
	 * @return Array of OntologyBeans
	 */
	public /*OntologyBean[]*/ function getPredicates(){
		return $this->predicates;
	}
	
	/**
	 * 
	 * Get the array of datas
	 * @return Array of OntologyBeans
	 */
	public /*OntologyBean[]*/ function getDatas(){
		return $this->datas;
	}
	
	/**
	 * 
	 * Get the number of predicates ontologies.
	 * Will always be between 1 and 4.
	 * @return number
	 */
	public /*int*/ function getNumberOfPredicates(){
		return count($this->predicates);
	}
	
	/**
	 * 
	 * Get the number of data ontologies 
	 * @return number
	 */
	public /*int*/ function getNumberOfDatas(){
		return count($this->datas);
	}
	
	/**
	 * 
	 * Get the number of ontologies, predicates + datas
	 * @return number
	 */
	public /*int*/ function getNumberOfOntologies(){
		return $this->getNumberOfPredicates() + $this->getNumberOfDatas();
	}
	
}
?>
