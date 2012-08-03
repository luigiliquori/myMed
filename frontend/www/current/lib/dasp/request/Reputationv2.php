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
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Reputationv2 extends Requestv2 {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private $id;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*String*/ $id) {
		parent::__construct("v2/ReputationRequestHandler", READ);
		$this->id = $id;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function send(){
		parent::addArgument("application", APPLICATION_NAME);
		parent::addArgument("producer", $this->id);

		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);

		$rep = 1;
		$nb = 0;

		if($responseObject->status == 200) {
			$rep = $responseObject->dataObject->reputation->reputation;
			$nb = $responseObject->dataObject->reputation->noOfRatings;
		} else if (!is_null($this->handler)) 
			$this->handler->setError($responseObject->description);

		return array(
				"rep" => round($rep * 100),
				"nbOfRatings" => $nb,
				"up" => $rep * $nb,
				"down" => $nb - $rep * $nb
		);

	}
}
?>
