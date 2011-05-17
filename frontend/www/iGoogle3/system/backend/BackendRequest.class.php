<?php
define('BackendRequest_CREATE'		, 0);
define('BackendRequest_READ'		, 1);
define('BackendRequest_UPDATE'		, 2);
define('BackendRequest_DELETE'		, 3);
class BackendRequest
{
	private /*string*/					$ressource;
	private /*BackendRequest_*/			$method;
	private /*Array<string,string>*/	$arguments	= Array();
	private /*string*/					$errorStr	= null;
	private /*int*/						$errorNo	= null;
	public function __construct(/*string*/ $ressource, /*BackendRequest_*/ $method=BackendRequest_READ)
	{
		$this->ressource	= $ressource;
		$this->method	= $method;
	}
	
	public /*void*/ function addArgument(/*string*/ $name, /*string*/ $value)
	{
		$this->arguments[$name]	= $value;
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
	
	public /*string*/ function send()
	{
		$curl	= curl_init();
		if($curl === false)
			trigger_error('Unable to init CURL : ', E_USER_ERROR);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$this->arguments['code']	= $this->method;
		switch($this->method)
		{
			case BackendRequest_CREATE:
			case BackendRequest_UPDATE:
			{
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $this->arguments);
			}break;
			case BackendRequest_READ:
			case BackendRequest_DELETE:
			{
				curl_setopt($curl, CURLOPT_URL, BACKEND_URL.$this->ressource.'?'.http_build_query($this->arguments));
			}break;
			default:
				curl_close($curl);
				trigger_error('BackendRequest method '.$this->method.' not suported', E_USER_ERROR);
		}
		/*
		$data 	= curl_exec($curl);/*/
		var_dump(curl_getinfo($curl));$data = '';//*/
		if($data === false)
		{
			$errorNo	= curl_errno($curl);
			$errorStr	= curl_error($curl);
		}
		curl_close($curl);
		return $data;
	}
}
?>
