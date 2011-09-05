<?php
require_once __DIR__.'/../library.php';
define('BackendRequest_CREATE'		, 0);
define('BackendRequest_READ'		, 1);
define('BackendRequest_UPDATE'		, 2);
define('BackendRequest_DELETE'		, 3);
class BackendRequestException extends HttpException{}

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
		//var_dump(curl_getinfo($curl));
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
