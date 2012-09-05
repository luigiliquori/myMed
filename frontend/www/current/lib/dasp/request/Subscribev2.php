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
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 *
 */
class Subscribev2 extends Requestv2 {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(
			$method = READ,
			$data,
			/*IRequestHandler*/ $handler) {
		parent::__construct("v2/SubscribeRequestHandler", $method, $data);
		$this->handler	 = $handler;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {

		parent::addArgument("user", $_SESSION['user']->id );

		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);

		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
			return null;
		} else {
			$this->handler->setSuccess("Request sent!");
			return $responseObject->dataObject;
		}
	}
}
?>
