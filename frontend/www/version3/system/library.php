<?php
/**
 * Redirect user on another page. Using it instead of header() can help debug
 * call header('Location:') then exit
 * if DEBUG=true, print linl with url the print traces
 */
/*void*/ function httpRedirect(/*string*/ $newUrl)
{
	if(defined('DEBUG')&&DEBUG)
	{
		echo '<p><a href="'.$newUrl.'">Redirection HTTP</a> (blocked for DEBUG, call ?debug=0 toremove it)</p>';
		echo '<h1>Traces</h1>';
		printTraces();
	}
	else
		header('Location:'.$newUrl);
	exit;
}
class CUrlException extends Exception{}
class HttpException extends Exception
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
/**
 * send an error message tu the final user usiing session then redirect to the 
 * current page in GET method
 * @param $msg	message to send
 * @param $removeGetVar	if true remove all var after '?'
 */
/*void*/ function sendError(/*string*/ $msg, /*bool*/ $removeGetVar=false)
{
	$_SESSION['error']	= $msg;
	if($removeGetVar)
		httpRedirect(preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']));
	else
		httpRedirect($_SERVER['REQUEST_URI']);
}
/**
 * print error send with sendError() inside a \<div class="error"\> tag
 */
/*void*/ function printError()
{
	if(isset($_SESSION['error']))
	{
		echo '<div class="error"><a href="'.$_SERVER['REQUEST_URI'].'" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">'.$_SESSION['error'].'</a></div>';
		unset($_SESSION['error']);
	}
}
?>
