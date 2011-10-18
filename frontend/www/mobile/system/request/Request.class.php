<?php

define('CREATE'		, 0);
define('READ'		, 1);
define('UPDATE'		, 2);
define('DELETE'		, 3);

/**
 *
 */
class Request {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*string*/					$ressource;
	private /*BackendRequest_*/			$method;
	private /*Array<string,string>*/	$arguments	= Array();
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public function __construct(/*string*/ $ressource, /*BackendRequest_*/ $method=READ) {
		$this->ressource	= $ressource;
		$this->method	= $method;
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function addArgument(/*string*/ $name, /*string*/ $value) {
		$this->arguments["$name"]	= "$value";
	}

	public /*void*/ function removeArgument(/*string*/ $name) {
		unset($this->arguments[$name]);
	}

	public /*void*/ function setMethod(/*BackendRequest_*/ $method) {
		$this->method	= $method;
	}

	public /*string*/ function send() {
// 		echo '<script type="text/javascript">alert("send method called to: ' . $this->ressource . '")</script>';
		$curl	= curl_init();
		if($curl === false)
		trigger_error('Unable to init CURL : ', E_USER_ERROR);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$httpHeader = Array('Accept: text/plain,application/json');
		$this->arguments['code']	= $this->method;
		
		switch($this->method) {
			case CREATE:
			case UPDATE:
				$httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->arguments));
				break;
			case READ:
			case DELETE:
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource.'?'.http_build_query($this->arguments));
				 break;
			default:
				break;
		}
		
		// SSL CONNECTION
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // see address in config.php
		curl_setopt($curl, CURLOPT_CAINFO, "/local/mymed/backend/WebContent/certificate/mymed.crt"); // TO EXPORT FROM GLASSFISH!
		
		$result = curl_exec($curl);
// 		echo '<script type="text/javascript">alert(\'' . $result . '\');</script>';
		return $result;
	}
}
?>
