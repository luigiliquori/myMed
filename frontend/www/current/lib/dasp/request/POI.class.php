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
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Find extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("POIRequestHandler", READ);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*string*/ function send() {
		
		// Construct the requests
		parent::addArgument("application", $_REQUEST['application']);
		

		parent::addArgument("type", $_REQUEST['type']);
		parent::addArgument("latitude", $_REQUEST['latitude']);
		parent::addArgument("longitude", $_REQUEST['longitude']);
		parent::addArgument("radius", $_REQUEST['radius']);
		
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
		} else {
			$this->handler->setSuccess($responseObject->data->results);
		}
		
		
		return $responsejSon;
	}
}
?>