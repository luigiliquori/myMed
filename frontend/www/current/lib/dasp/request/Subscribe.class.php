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
require_once 'lib/dasp/beans/MDataBean.class.php';
require_once 'system/templates/handler/IRequestHandler.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Subscribe extends Request {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*IRequestHandler*/ $handler) {
		parent::__construct("SubscribeRequestHandler", CREATE);
		$this->handler	= $handler;
	}
	
	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		parent::addArgument("application", $_POST['application']);
		// construct the predicate + data
		$predicate = "";
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
			$ontology->value = $_POST[$ontology->key];
			if($ontology->value != "") {
				$predicate .= $ontology->key . "(" . $ontology->value . ")";
			}
		}
		// construct the request
		parent::addArgument("predicate", $predicate);
		parent::addArgument("user", json_encode($_SESSION['user']));
	
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
		
		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
		} else {
			$this->handler->setSuccess("Request sent!");
		}
	}
}
?>
