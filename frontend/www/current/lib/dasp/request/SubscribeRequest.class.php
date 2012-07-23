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

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class SubscribeRequest extends Request {
	
	// Attributes
	private /** String */     $userID;
	private /** DataBean[] */ $predicateList;
	private /** String*/      $namespace;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(
			/** String */     $userID,
			/** DataBean[] */ $predicateList,
			/** String*/      $namespace) 
	{
		parent::__construct("SubscribeRequestHandler", CREATE);
		$this->userId = $userID;
		$this->predicateList = $predicateList;
		$this->namespace = $namespace;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		
		// Default userID => current one
		if (empty($this->userID)) {
			$this->userID = $_SESSION['user']->id;
		}
		parent::addArgument("userID", $this->userID);
		
		// Application/namespace
		if ($this->namespace == null) {
			parent::addArgument("application", APPLICATION_NAME);
		} else {
			parent::addArgument("application", APPLICATION_NAME . ":$this->namespace");
		}

		// Predicate
		parent::addArgument("predicateList", json_encode($this->predicateList));
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);

		if ($responseObject->status != 200) {
			throw new Exception("Error in SubscribeRequest: " . $responseObject->description);
		}

		return $responseObject;
	}
}
?>
