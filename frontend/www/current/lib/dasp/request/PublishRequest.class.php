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
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class PublishRequest extends Request {

	private /* IRequestHandler */ 	$handler;
	private /* DataBean  */	    	$dataBean;
	private /* String    */			$userID;
	private /* Namespace */			$namespace;

	public function __construct(
		/* IRequestHandler*/ $handler, 
	 	/* DataBean */ $dataBean, 
	 	/* String*/ $userID = null,
		/* String */ $namespace = null) 
	{
		parent::__construct("PublishRequestHandler", CREATE);
		$this->handler	 = $handler;
		$this->dataBean  = $dataBean;
		$this->userID    = $userID;
		$this->namespace = $namespace;
		$this->setMultipart(true);
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {	

		// Build the request
		if (empty($this->userID)) {
			$this->userID = $_SESSION['user']->id;
		} 
		
		// PREDICATES
		$predicate = json_encode($this->dataBean->getPredicates());
		// DATAS
		$data = json_encode($this->dataBean->getDatas());

			
		// Construct the requests
		if ($this->namespace == null) {
			parent::addArgument("application", APPLICATION_NAME);
		} else {
			parent::addArgument("application", APPLICATION_NAME . ":$this->namespace");
		}
		parent::addArgument("user", $this->userID);
		parent::addArgument("predicate", $predicate);
		parent::addArgument("data", $data);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
			
		if ($responseObject->status != 200) {
			if (is_null($this->handler)) {
				throw new Exception("Error in PublishRequest: " . $responseObject->description);
			} else {
				$this->handler->setError($responseObject->description);
			}
		} 
			
	}
}
?>
