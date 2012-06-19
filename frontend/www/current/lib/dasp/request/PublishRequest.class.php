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
require_once dirname(dirname(__FILE__)) . '/beans/DataBean.php';
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class PublishRequest extends Request {


	private /*IRequestHandler*/ 	$handler;
	private /*DataBean*/			$dataBean;
	private /*String*/				$publisher;


	public function __construct(/*IRequestHandler*/ $handler, /** DataBean */ $dataBean, /*String*/ $publisher = NULL ) {
		parent::__construct("PublishRequestHandler", CREATE);
		$this->handler	= $handler;
		$this->dataBean = $dataBean;
		$this->publisher = $publisher;
		
		$this->setMultipart(true);
		
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {		
		/*
		 * Build the request
		 */
		
		// APPLICATION
		$application = APPLICATION_NAME;
		
		// USER
		/* If the publisher isn't the current user, fetch the profile */
		if( !empty($this->publisher) ) {
			$request = new Request("ProfileRequestHandler", READ);
			$request->addArgument("id",  $this->publisher);
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			$user = $responseObject->data->user;
		} else {
			// publisher = the current user
			$user = json_encode($_SESSION['user']);
		}
		
		// PREDICATES
		$predicate = json_encode($this->dataBean->getPredicates());
		// DATAS
		$data = json_encode($this->dataBean->getDatas());

			
		parent::addArgument("application", $application);
		parent::addArgument("user", $user);
		parent::addArgument("predicate", $predicate);
		parent::addArgument("data", $data);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
			
		if ($responseObject->status != 200) {
			if (is_null($this->handler)) {
				throw new Exception($responseObject->description);
			} else {
				$this->handler->setError($responseObject->description);
			}
		} 
			
	}
}
?>
