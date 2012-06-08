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
 * Represente a Data in the myMed model
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
			return;//TODO : error. (exception?)
		
		// elements should be of type KEYWORD, GPS, ENUM or DATE
		// and not be empty
		foreach ($predicates as $data)
		{
			if ($data->ontologyID >= 4)
				return; //TODO : error
			
			if ( $data->value == "")
				return; //TODO : error
		}
		
		$this->predicates = $predicates;
		$this->datas = $datas;
		
	}
	
	
	public /*OntologyBean[]*/ function getPredicates(){
		return $this->predicates;
	}
	
	public /*OntologyBean[]*/ function getDatas(){
		return $this->datas;
	}
	
	public /*int*/ function getNumberOfPredicates(){
		return count($this->predicates);
	}
	
	public /*int*/ function getNumberOfDatas(){
		return count($this->datas);
	}
	
	public /*int*/ function getNumberOfOntologies(){
		return $this->getNumberOfPredicates() + $this->getNumberOfDatas();
	}
	
}
?>
