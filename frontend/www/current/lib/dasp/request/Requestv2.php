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
 *
 */
class Requestv2 {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/					$url;
	private /*string*/					$ressource;
	private /*BackendRequest_*/			$method;
	private /*Array<string,string>*/	$arguments;
	private /*Boolean>*/				$multipart;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*string*/ $ressource, /*BackendRequest_*/ $method=READ, $data= array()) {
		$this->ressource	= $ressource;
		$this->method		= $method;
		$this->url			= BACKEND_URL;
		$this->multipart	= false;
		$this->arguments    = $data;
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function addArgument(/*string*/ $name, /*string*/ $value) {
		// 		$this->arguments["$name"] = urlencode(strtolower($value));
		if($this->multipart){
			$this->arguments[$name] = urlencode($value);
		} else {
			$this->arguments[$name] = $value; //httpbuildquery already encodes
		}
		
	}
	
	public /*void*/ function addArguments(/*array*/ $args) {
		$this->arguments = array_replace_recursive($this->arguments, $args);
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
		if($this->multipart){
			$httpHeader = array('Content-Type:multipart/form-data');
		} else {
			$httpHeader = array('Content-Type:application/x-www-form-urlencoded');
		}
		$this->arguments['code'] = $this->method;

		// Token for security - to access to the API
		if(isset($_SESSION['accessToken'])) {
			$this->arguments['accessToken'] = $_SESSION['accessToken'];
		}

		if($this->method == CREATE || $this->method == UPDATE){
			// POST REQUEST
			curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
			curl_setopt($curl, CURLOPT_URL, $this->url.$this->ressource);
			curl_setopt($curl, CURLOPT_POST, true);
			if($this->multipart){
				curl_setopt($curl, CURLOPT_POSTFIELDS, $this->arguments);
			} else {
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->arguments));
			}
				
		} else {
			// GET REQUEST
			curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
			curl_setopt($curl, CURLOPT_URL, $this->url.$this->ressource.'?'.http_build_query($this->arguments));
		}
		
// 		echo $this->url.$this->ressource.'?'.http_build_query($this->arguments) . '<br /><br />';
		
		// SSL CONNECTION
		// TODO fix once we have the valid certificate!
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
		curl_setopt($curl, CURLOPT_CAINFO, "/etc/ssl/certs/mymed.crt"); // TO EXPORT FROM GLASSFISH!

		$result = curl_exec($curl);
//  		echo $result . '<br /><br />';

		if ($result === false) {
			throw new Exception("CURL Error : " . curl_error($curl));
		}
		
		return $result;
	}
}
?>
