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
require_once '../../lib/dasp/request/Publish.class.php';
require_once '../../lib/dasp/request/Find.class.php';
require_once '../../lib/dasp/request/GetDetail.class.php';
require_once '../../lib/dasp/request/Delete.class.php';
require_once '../../lib/dasp/request/Subscribe.class.php';
require_once '../../lib/dasp/request/Request.class.php';

/**
 * 
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class MyApplicationHandler implements IRequestHandler {
	
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
		
		if (isset($_GET['predicate'])) { // HANDLE unsubscription from mail
			$request = new Request("SubscribeRequestHandler", DELETE);
			$request->addArgument("application", $_GET['application']);
			$request->addArgument("predicate", $_GET['predicate']);
			$request->addArgument("userID", $_GET['userID'] );
			if (isset($_GET['accessToken']))
				$request->addArgument('accessToken', $_GET['accessToken']);
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
			if ($responseObject->status==200){
				header("Refresh:0;url=/application/" . APPLICATION_NAME."Admin#SubscribeView");
			}
		}
		
		
		if(isset($_POST['method'])) {
			if($_POST['method'] == "publish") {
				$delete = new Delete($this);
				$delete->send();
				$publish = new Publish($this);
				$publish->send();
				$_POST['application'] = APPLICATION_NAME . "_ADMIN";
				$delete = new Delete($this);
				$delete->send();
			} else if($_POST['method'] == "subscribe") {
				$subscribe = new Subscribe($this);
				$subscribe->send();
			} else if($_POST['method'] == "find") {
				$find = new Find($this);
				$find->send();
			} else if($_POST['method'] == "getDetail") {
				$getDetail = new GetDetail($this);
				$getDetail->send();
			} else if($_POST['method'] == "delete") {
				$delete = new Delete($this);
				$delete->send();
				$_POST['application'] = APPLICATION_NAME . "_ADMIN";
				$delete = new Delete($this);
				$delete->send();
			} else if($_POST['method'] == "startInteraction") {
				$startInteraction = new StartInteraction($this);
				$startInteraction->send();
			} 
		} 
	}
	
	/* --------------------------------------------------------- */
	/* Getter&Setter */
	/* --------------------------------------------------------- */
	public /*String*/ function getError(){
		return $this->error;
	}
	
	public /*void*/ function setError(/*String*/ $error){
		return $this->error = $error;
	}
	
	public /*String*/ function getSuccess(){
		return $this->success;
	}
	
	public /*void*/ function setSuccess(/*String*/ $success){
		return $this->success = $success;
	}
	
}
?>