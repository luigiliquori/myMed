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

class Requestv2Wrapper extends Requestv2 {

	public function __construct(
			$handler=null,
			$data= array(),
			$method=READ,
			$ressource = "v2/PublishRequestHandler") {
		parent::__construct($ressource, $method, $data);
		$this->handler = $handler;
	}

	public /*string*/ function send() {
		$result = parent::send();
		$obj = json_decode($result);
		
		if ($obj->status != 200 && !is_null($this->handler)){
			$this->handler->setError($obj->description);
			throw new Exception("No results found");
		
		} else {// Success
			return $obj->dataObject;
		}
		
	}
}
?>
