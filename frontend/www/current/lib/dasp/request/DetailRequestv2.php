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
require_once dirname(__FILE__).'/Requestv2.php';
//require_once dirname(dirname(__FILE__)) . '/beans/DataBean.php';
require_once dirname(__FILE__).'/IRequestHandler.php';


/**
 *
 * Find Request.
 * If "user" is provided, if fetches detail data for a specific publication.
 * Otherwise, it returns a list of publications.
 * @author David Da Silva
 *
 */
class DetailRequestv2 extends Requestv2 {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private $namespace;
	private /*String*/ $id;
	

	/** 
	 * Constructor
	 */
	public function __construct(
		$handler, $namespace, $id) 
	{
		parent::__construct("v2/PublishRequestHandler", READ);
		$this->handler	= $handler;
		$this->id = $id;
		$this->namespace = $namespace;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*string*/ function send() {

		// Construct the requests

		parent::addArgument("application", APPLICATION_NAME);
		if (!empty($this->namespace))
			parent::addArgument("namespace", $this->namespace);
		if (!empty($this->id))
			parent::addArgument("id", $this->id);

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
				
			// Get details for one object

			// There shouldn't be empty results
			if ($responseObject->status == 404) {
				throw new Exception("No publication found for predicate:$this->id ");
			} else {
				$result = $responseObject->dataObject->details;
			}
		
		
		} // End of "Success"
			
		if (!is_null($this->handler) && isset($result)) {
			// Set result in handler
			// XXX: Dirty !!
			$this->handler->setSuccess($result);
		}
			
		return $result;
	}

}
?>
