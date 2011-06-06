<?php
define('BackendRequest_CREATE'		, 0);
define('BackendRequest_READ'		, 1);
define('BackendRequest_UPDATE'		, 2);
define('BackendRequest_DELETE'		, 3);
class BackendRequestException extends Exception
{
	private /*string*/	$httpContent;
	public function __construct(/*int*/ $code, /*string*/ $httpContent='')
	{
    	parent::__construct(null, $code, null);
    	$len = strlen($httpContent);
    	if(8<$len && $len<128)
    		$this->message	= $httpContent;
    	else
    		$this->message	= $this->httpCodeTranslate();
	}
	
	public /*string*/ function httpCodeTranslate()
	{
    	switch($this->code)
    	{// src : http://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
			case 100	: return 'Continue';
			case 101	: return 'Switching Protocols';
			case 102	: return 'Processing';
			case 200	: return 'OK';
			case 201	: return 'Created';
			case 202	: return 'Accepted';
			case 203	: return 'Non-Authoritative Information';
			case 204	: return 'No Content';
			case 205	: return 'Reset Content';
			case 206	: return 'Partial Content';
			case 207	: return 'Multi-Status';
			case 210	: return 'Content Different';
			case 300	: return 'Multiple Choices';
			case 301	: return 'Moved Permanently';
			case 302	: return 'Found';
			case 303	: return 'See Other';
			case 304	: return 'Not Modified';
			case 305	: return 'Use Proxy';
			case 307	: return 'Temporary Redirect';
			case 310	: return 'Too many Redirect';
			case 400	: return 'Bad Request';
			case 401	: return 'Unauthorized';
			case 402	: return 'Payment Required';
			case 403	: return 'Forbidden';
			case 404	: return 'Not Found';
			case 405	: return 'Method Not Allowed';
			case 406	: return 'Not Acceptable';
			case 407	: return 'Proxy Authentication Required';
			case 408	: return 'Request Time-out';
			case 409	: return 'Conflict';
			case 410	: return 'Gone';
			case 411	: return 'Length Required';
			case 412	: return 'Precondition Failed';
			case 413	: return 'Request Entity Too Large';
			case 414	: return 'Request-URI Too Long';
			case 415	: return 'Unsupported Media Type';
			case 416	: return 'Requested range unsatisfiable';
			case 417	: return 'Expectation failed';
			case 418	: return 'I’m a teapot';
			case 422	: return 'Unprocessable entity';
			case 423	: return 'Locked';
			case 424	: return 'Method failure';
			case 425	: return 'Unordered Collection';
			case 426	: return 'Upgrade Required';
			case 449	: return 'Retry With';
			case 450	: return 'Blocked by Windows Parental Controls';
			case 500	: return 'Internal Server Error';
			case 501	: return 'Not Implemented';
			case 502	: return 'Bad Gateway ou Proxy Error';
			case 503	: return 'Service Unavailable';
			case 504	: return 'Gateway Time-out';
			case 505	: return 'HTTP Version not supported';
			case 507	: return 'Insufficient storage';
			case 509	: return 'Bandwidth Limit Exceeded';
			default	:
			if(100<=$this->code && $this->code<200)
				return 'Unknown Information Code';
			elseif(200<=$this->code && $this->code<300)
				return 'Unknown Succes Code';
			elseif(300<=$this->code && $this->code<400)
				return 'Unknown Redirect Code';
			elseif(400<=$this->code && $this->code<500)
				return 'Unknown Client Error';
			elseif(500<=$this->code && $this->code<600)
				return 'Unknown Serveur Error';
    	}
	}
	
	public /*string*/ function getHttpContent()
	{
		return $this->httpContent;
	}
}

class CUrlException extends Exception{}

class BackendRequest
{
	private /*string*/					$ressource;
	private /*BackendRequest_*/			$method;
	private /*Array<string,string>*/	$arguments	= Array();
	public function __construct(/*string*/ $ressource, /*BackendRequest_*/ $method=BackendRequest_READ)
	{
		$this->ressource	= $ressource;
		$this->method	= $method;
	}
	
	public /*void*/ function addArgument(/*string*/ $name, /*string*/ $value)
	{
		$this->arguments["$name"]	= "$value";
	}
	
	public /*void*/ function removeArgument(/*string*/ $name)
	{
		unset($this->arguments[$name]);
	}
	
	public /*void*/ function setMethod(/*BackendRequest_*/ $method)
	{
		$this->method	= $method;
	}
	
	public /*string*/ function getCurlErrorStr()
	{
		return $this->errorStr;
	}
	
	public /*int*/ function getCurlErrorNo()
	{
		return $this->errorNo;
	}
	
	public /*string*/ function send() /*throws BackendRequestException, CUrlException*/
	{
		$curl	= curl_init();
		if($curl === false)
			trigger_error('Unable to init CURL : ', E_USER_ERROR);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$httpHeader = Array('Accept: text/plain,application/json');
		$this->arguments['code']	= $this->method;
		switch($this->method)
		{
			case BackendRequest_CREATE:
			case BackendRequest_UPDATE:
			{
				$httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->arguments));
			}break;
			case BackendRequest_READ:
			case BackendRequest_DELETE:
			{
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource.'?'.http_build_query($this->arguments));
				//curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource.'?toto=tata');
			}break;
			default:
				curl_close($curl);
				trigger_error('BackendRequest method '.$this->method.' not suported', E_USER_ERROR);
		}
		$data 	= curl_exec($curl);
	//var_dump($data);	
		if($data === false)
			$excurl = new CUrlException(curl_error($curl), curl_errno($curl));
		$httpCode	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if( !(200<=$httpCode && $httpCode<300) )
			$exhttp = new BackendRequestException($httpCode, htmlspecialchars($data));
		
		curl_close($curl);
		
		if(isset($excurl))
			throw $excurl;
		if(isset($exhttp))
			throw $exhttp;
		return $data;
	}
}
?>
