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

/**
 * the diff with Request is it's sent here in Json
 */


class RequestPubSub {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/					$url;
	private /*string*/					$ressource;
	private /*BackendRequest_*/			$method;
	private /*Array<string,string>*/	$arguments	= array();
	private /*Boolean>*/				$multipart;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(
			$data= array(),
			$method=READ,
			$ressource = "v2/PublishRequestHandler") {
		$this->url			= BACKEND_URL;
		$this->multipart	= false;
		$this->arguments    = $data;
		$this->method		= $method;
		$this->ressource	= $ressource;
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function addArgument(/*string*/ $name, /*string*/ $value) {		
		$this->arguments[$name] = $value;
	}
	public /*void*/ function addArguments(/*array*/ $args) {
		$this->arguments = array_replace_recursive($this->arguments, $args);
	}
	public /*void*/ function hasArgument(/*string*/ $name) {
		return isset($this->arguments[$name]);
	}

	public /*void*/ function removeArgument(/*string*/ $name) {
		unset($this->arguments[$name]);
	}

	public /*void*/ function setMethod(/*BackendRequest_*/ $method) {
		$this->method	= $method;
	}

	public /*Boolean*/ function setURL($url) {
		$this->url = $url;
	}

	public /*Boolean*/ function setMultipart($multipart = false) {
		$this->multipart = $multipart;
	}

	public /*Boolean*/ function isMultipart() {
		return $this->multipart;
	}

	public /*string*/ function send() {
		$curl	= curl_init();
		if($curl === false) {
			trigger_error('Unable to init CURL : ', E_USER_ERROR);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$this->arguments['code'] = $this->method;

		// Token for security - to access to the API
		if(isset($_SESSION['accessToken'])) {
			$this->arguments['accessToken'] = $_SESSION['accessToken'];
		}
		// POST REQUEST
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($curl, CURLOPT_URL, $this->url.$this->ressource);
		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->arguments));
		
		// SSL CONNECTION
		// TODO fix once we have the valid certificate!
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
		curl_setopt($curl, CURLOPT_CAINFO, "/etc/ssl/certs/mymed.crt"); // TO EXPORT FROM GLASSFISH!

		$result = curl_exec($curl);
 		//echo '<script type="text/javascript">alert(\'' . $result . '\');</script>';

		if ($result === false) {
			throw new Exception("CURL Error : " . curl_error($curl));
		}
		
		return json_decode($result);
	}
}
?>
