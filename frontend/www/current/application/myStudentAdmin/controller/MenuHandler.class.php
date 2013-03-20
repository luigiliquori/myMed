<!--
 * Copyright 2013 INRIA
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
 -->
<?php
require_once '../../lib/dasp/request/Request.class.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MenuHandler implements IRequestHandler {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/ $error;
	private /*string*/ $success;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct() {
		$this->error	= false;
		$this->success	= false;
		$this->handleRequest();
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function handleRequest() {
		if(isset($_POST['disconnect']) || isset($_GET['disconnect'])) {
			
			// DELETE BACKEND SESSION
			$request = new Request("SessionRequestHandler", DELETE);
			$request->addArgument("accessToken", $_SESSION['user']->session);
			$request->addArgument("socialNetwork", $_SESSION['user']->socialNetworkName);

			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			if($responseObject->status != 200) {
				$this->error = $responseObject->description;
			}
				
			// DELETE FRONTEND SESSION
			session_destroy();
			if (isset($_SESSION['wrapper'])){
				header("Refresh:0;url=".$_SESSION['wrapper']->getLogoutUrl());
			}
			header("Refresh:0;url=".$_SERVER['PHP_SELF']. "?application=" . APPLICATION_NAME);
		} 
	}

	public /*String*/ function getError(){
		return $this->error;
	}

	public /*String*/ function getSuccess(){
		return $this->success;
	}
}
?>
