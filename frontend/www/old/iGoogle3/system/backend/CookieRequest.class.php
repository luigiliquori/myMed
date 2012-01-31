<?php
require_once __DIR__.'/DHTRequest.class.php';
class CookieNotFoundException extends Exception
{
	public function __construct(/*string*/ $cookieName, Exception $previous=null)
	{
		parent::__construct('Cookie "'.$cookieName.'" Not Found', 0, $previous);
	}
}
class CookieRequest
{
	private /*DHTRequest*/ $request;
	public function __construct()
	{
		$this->request	= new DHTRequest;
	}
	public /*void*/ function create(/*string*/ $name, /*string*/ $value) /*throws BackendRequestException, CUrlException*/
	{
		if(USER_CONNECTED)
			$this->request->create($_SESSION['user']->id.$name, $value);
		else
			setcookie($name, $value, time() + 365*24*3600, ROOTPATH);
	}
	public /*string*/ function read(/*string*/ $name) /*throws BackendRequestException, CUrlException*/
	{
		if(USER_CONNECTED)
		{
			try	{return $this->request->read($_SESSION['user']->id.$name);}
			catch(BackendRequestException $ex)
			{
				if($ex->getCode() != 404)
					throw $ex;
				throw new CookieNotFoundException($name, $ex);
			}
		}
		elseif(isset($_COOKIE[$name]))
			return $_COOKIE[$name];
		else
			throw new CookieNotFoundException($name);
	}
	public /*void*/ function update(/*string*/ $name, /*string*/ $value) /*throws BackendRequestException, CUrlException*/
	{
		$this->create();
	}
	public /*void*/ function delete(/*string*/ $name) /*throws BackendRequestException, CUrlException*/
	{
		if(USER_CONNECTED)
			$this->request->delete($_SESSION['user']->id.$name);
		else
			setcookie($name);
	}
}
?>
