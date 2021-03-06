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
require_once dirname(__FILE__).'/Request.class.php';
require_once dirname(dirname(__FILE__)) . '/beans/DataBean.php';
require_once dirname(__FILE__).'/IRequestHandler.php';


/**
 *
 * Find Request.
 * If "user" is provided, if fetches detail data for a specific publication.
 * Otherwise, it returns a list of publications.
 * @author David Da Silva
 *
 */
class FindRequest extends Request {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private /*String*/ $predicate;
	private /*String*/ $user;
	private $namespace;

	/** 
	 * Constructor
	 */
	public function __construct(
		/** IRequestHandler */ $handler, 
		/* MDataBean[] */ $predicateList,
	    /* String*/ $user,
		/* String*/ $namespace = null) 
	{
		parent::__construct("FindRequestHandler", READ);
		$this->handler	= $handler;
		$this->predicateList = $predicateList;
		$this->predicate = $predicateList;
		$this->user = $user;
		$this->namespace = $namespace;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*string*/ function send($count = 100, $APPLICATION_NAME = APPLICATION_NAME) {

		// Construct the requests
		parent::addArgument("count", $count);
		
		// Overrride_APPLICATION_NAME when the request 
		// is called from a different app from that published it
		if($APPLICATION_NAME != APPLICATION_NAME) {
			parent::addArgument("application", $APPLICATION_NAME);
		} elseif ($this->namespace == null) {
			parent::addArgument("application", APPLICATION_NAME);
		} else {
			parent::addArgument("application", APPLICATION_NAME . ":$this->namespace");
		}
		
		// Backward compatibility with plain concatenate predicate
		if (gettype($this->predicateList) == "string") {
			parent::addArgument("predicate", $this->predicateList);
		} else {
			parent::addArgument("predicateList", json_encode($this->predicateList));
		}

		
		// User => Then get details
		if (!empty($this->user)) {
			parent::addArgument("user", $this->user);
		}

		// Classical matching
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
	
		if ($responseObject->status != 200 && $responseObject->status != 404) { // Error
				
			if (!is_null($this->handler)) {
				$this->handler->setError($responseObject->description);
			} else {
				throw new Exception($responseObject->description);
			}
				
		} else { // Success
				
			// Result (either list of results or details)
			if (empty($this->user)) { // Get objects matching predicates 
				
				if ($responseObject->status == 404) {
					// Empty list for results is ok
					$result = array();
				} else {
					$result = $responseObject->dataObject->results;
				}

			} else { // Get details for one object
				
				// There shouldn't be empty results
				if ($responseObject->status == 404) {
					throw new Exception("No publication found for predicate:$this->predicate User:$this->user");
				} else {
					$result = $responseObject->dataObject->details;
				}
				
			} // End of "get list of objects"  
		
		} // End of "Success"
			
		if (!is_null($this->handler)) {
			// Set result in handler
			// XXX: Dirty !!
			$this->handler->setSuccess($result);
		}
			
		return $result;
	}
}
?>
