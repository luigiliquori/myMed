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
require_once 'lib/dasp/request/Request.class.php';
require_once 'system/templates/handler/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Reputation extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		parent::__construct("ReputationRequestHandler", READ);
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function getReputation(/*String*/ $application, /*String*/ $producer, /*String*/ $consumer){
		parent::addArgument("application", $application);
		parent::addArgument("producer", $producer);
		parent::addArgument("consumer", $consumer);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status == 200) {
			return $responseObject->data->reputation;
		} else {
			return $responseObject->description;
		}
	}
}
?>
