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
require_once dirname(__FILE__).'/../beans/DataBean.php';
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 *
 * Request Handler for deletion
 * @author lvanni
 *
 */
class DeleteRequest extends Request {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private $userID;
	private $predicateList;
	private $namespace;

	/* --------------------------------------------------------- */
	/* Constructors
	/* --------------------------------------------------------- */
	
	/**
	 * @param $predicateList MDataBean[] 
	 */
	public function __construct(
			$userID, 
			$predicateList, 
			$namespace=null) 
	{
		parent::__construct("PublishRequestHandler", DELETE);
		$this->predicateList = $predicateList;
		$this->userID = $userID;
		$this->namespace = $namespace; 
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send($APPLICATION_NAME = APPLICATION_NAME) {

		// Construct the requests
		
		// Overrride_APPLICATION_NAME when the request
		// is called from a different app from that published it
		if($APPLICATION_NAME != APPLICATION_NAME) {
			parent::addArgument("application", $APPLICATION_NAME);
		} elseif($this->namespace == null) {
			parent::addArgument("application", APPLICATION_NAME);
		} else {
			parent::addArgument("application", APPLICATION_NAME . ":$this->namespace");
		}
		
		if (empty($this->userID)) {
			throw new Exception("UserID not set in delete request");
		}
		
		parent::addArgument("userID", $this->userID);
		parent::addArgument("predicate", json_encode($this->predicateList));
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		// Error
		if ($responseObject->status != 200) {
			throw new Exception($responseObject->description);
		}
			
	}
}
?>
