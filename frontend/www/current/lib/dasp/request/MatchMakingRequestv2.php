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


class MatchMakingRequestv2 extends Requestv2 {

	private /* IRequestHandler */ 	$handler;
	private /* Namespace */			$namespace;

	public function __construct(
			$ressource = "v2/PublishRequestHandler",
			$method = READ,
			$data,
			$namespace = null,
			/* IRequestHandler*/ $handler=null ) 
	{
		parent::__construct($ressource, $method, $data);
		$this->handler	 = $handler;
		$this->namespace = $namespace;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {		
		
		parent::addArgument("application", APPLICATION_NAME);
		if (!empty($this->namespace))
			parent::addArgument("namespace", $this->namespace);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
			
		if ($responseObject->status != 200 && $responseObject->status != 404) { // Error
		
			if (!is_null($this->handler))
				$this->handler->setError($responseObject->description);
			else
				throw new Exception($responseObject->description);
		
		} else { // Success
			
			if ($responseObject->status == 404)
				$res = array();
			else{
				if (isset($responseObject->dataObject->results))
					$res = $responseObject->dataObject->results;
				else if (isset($responseObject->dataObject->details))
					$res = $responseObject->dataObject->details;
				else if (isset($responseObject->dataObject->subscriptions))
					$res = $responseObject->dataObject->subscriptions;
				else 
					$res = (array) $responseObject;
			}
		}

		return $res;
			
	}
}
?>
