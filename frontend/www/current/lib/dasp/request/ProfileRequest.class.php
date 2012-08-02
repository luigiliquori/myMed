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
 * Request handler to get profiles
 */
class ProfileRequest extends Request {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private $userID;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct($userID) 
	{
		parent::__construct("ProfileRequestHandler", READ);
		$this->userID = $userID;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		
		parent::addArgument("userID", $this->userID);

		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		// Error
		if ($responseObject->status != 200) {
			throw new Exception($responseObject->description);
		}
		
		return $responseObject->dataObject->user;
			
	}
}
?>
