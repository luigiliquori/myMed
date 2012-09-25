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
	private $producer;
	private $id;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct($handler, $producer = null, $id = null) {
		parent::__construct("v2/ReputationRequestHandler", READ);
		$this->handler = $handler;
		$this->producer = $producer;
		$this->id = $id;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*String*/ function send(){
		parent::addArgument("application", isset($_GET['application'])?$_GET['application']:APPLICATION_NAME);
		parent::addArgument("consumer", $_SESSION['user']->id );
		parent::addArgument("producer", $this->producer );
		if (!empty($this->id)){
			if ( is_array($this->id))
				parent::addArgument("ids", json_encode($this->id));
			else
				parent::addArgument("id", $this->id);
		}
		

		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);

		$rep = 1;
		$nb = 0;
		
		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
		} else {
			if ( !is_array($this->id)){
				$rep = $responseObject->dataObject->reputation->reputation;
				$nb = $responseObject->dataObject->reputation->numberOfVerdicts;
			
				return array(
						"rep" => round($rep * 100),
						"nbOfRatings" => $nb,
						"up" => $rep * $nb,
						"down" => $nb - $rep * $nb,
						"rated" => $responseObject->dataObject->reputation->rated
				);
			} else {
				$res = array();
				foreach ($responseObject->dataObject->reputation as $k=>$v){
					$res[$k] = array(
							"rep" => round($v->reputation * 100),
							"nbOfRatings" => $v->numberOfVerdicts,
							"up" => $v->reputation * $v->numberOfVerdicts,
							"down" => $v->numberOfVerdicts - $v->reputation * $v->numberOfVerdicts,
							"rated" => $v->rated
					);
				}
				return $res;
			}
		
		}
		

	}
}
?>
