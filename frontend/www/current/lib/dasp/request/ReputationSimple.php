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

/**
 *
 */
class ReputationSimple extends Requestv2 {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private $producer;
	private $id;
	private $application;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct($application, $producer = null, $id = null) {
		parent::__construct("v2/ReputationRequestHandler", READ);
		$this->producer = $producer;
		$this->id = $id;
		$this->application = $application;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function send(){
		parent::addArgument("application", $this->application);
		parent::addArgument("consumer", $_SESSION['user']->id );
		if (!empty($this->producer)){
			if ( is_array($this->producer))
				parent::addArgument("producer", json_encode($this->producer));
			else
				parent::addArgument("producer", $this->producer);
		}
		if (!empty($this->id)){
			if ( is_array($this->id))
				parent::addArgument("id", json_encode($this->id));
			else
				parent::addArgument("id", $this->id);
		}
		

		$responsejSon = parent::send();
		return json_decode($responsejSon);

	}
}
?>
