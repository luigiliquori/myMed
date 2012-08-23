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


class SimpleRequestv2 extends Requestv2 {

	private /* IRequestHandler */ 	$handler;

	public function __construct(
			$data = null,
			$ressource = "v2/DataRequestHandler",
			$method = READ,
			/* IRequestHandler*/ $handler=null ) 
	{
		parent::__construct($ressource, $method, $data);
		$this->handler	 = $handler;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if ($responseObject->status != 200 && $responseObject->status != 404 && !is_null($this->handler)) { // Error
			$this->handler->setError($responseObject->description);
			return null;
		
		} else // Success
			return $responseObject->dataObject;
	}
}
?>
