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
require_once dirname(__FILE__).'/../beans/DataBean.class.php';
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class Publish extends Request {


	private /*IRequestHandler*/ 	$handler;
	private /*DataBean*/			$dataBean;


	public function __construct(/*IRequestHandler*/ $handler, DataBean $dataBean) {
		parent::__construct("PublishRequestHandler", CREATE);
		$this->handler	= $handler;
		$this->setMultipart(true);
		
		
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {
		$predicateArray;
		$dataArray;
		$numberOfPredicate = 0;
		$numberOfOntology = 0;

		// construct the predicate + data
		for($i=0 ; $i<$_POST['numberOfOntology'] ; $i++){
				
			/*MDataBean*/ $ontology = json_decode(urldecode($_POST['ontology' . $i]));
				
			
			if(isset($_POST[$ontology->key])) {
				$ontology->value = $_POST[$ontology->key];
			} else {
				continue;
			}
				
			// construct the predicate
			if($ontology->ontologyID < 4 && $ontology->value != "") {
				$predicateArray[$numberOfPredicate++] = $ontology;
			}

			// construct the data
			$dataArray[$numberOfOntology++] = $ontology;
		}

		// Construct the requests
		$application = $_POST['application'];
		if(isset($_POST['publisher'])) {
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id",  $_POST['publisher']);
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			$user = $responseObject->data->user;
		} else {
			$user = json_encode($_SESSION['user']);
		}
		$predicate = json_encode($predicateArray);
		$data = json_encode($dataArray);

		// 		if (TARGET == "desktop") {
			
		parent::addArgument("application", $application);
		parent::addArgument("user", $user);
		parent::addArgument("predicate", $predicate);
		parent::addArgument("data", $data);
			
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) {
			$this->handler->setError($responseObject->description);
		} else {
			// 				$this->handler->setSuccess($responseObject->description);
			$this->handler->setSuccess("Request sent!");
		}
			
		return $responsejSon;
			
		// TODO WORK ON THE PICTURE UPLOAD
		// 		} else { // TARGET == "mobile"
		// 			header("Refresh:0;url=mobile_binary". MOBILE_PARAMETER_SEPARATOR
		// 			."publish" . MOBILE_PARAMETER_SEPARATOR
		// 			. urlencode($application) . MOBILE_PARAMETER_SEPARATOR
		// 			. urlencode($user) . MOBILE_PARAMETER_SEPARATOR
		// 			. urlencode($predicate) . MOBILE_PARAMETER_SEPARATOR
		// 			. urlencode($data) . MOBILE_PARAMETER_SEPARATOR
		// 			. urlencode($_SESSION['accessToken']));
					
		// 		}
		}
}
?>
