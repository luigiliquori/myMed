<?php
require_once dirname(__FILE__).'/AbstractBackendRequest.class.php';
require_once dirname(__FILE__).'/Profile.class.php';
class SessionRequest extends AbstractBackendRequest
{
	public /*Profile|false*/ function create(/*string*/ $login, /*string*/ $password) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		$this->addArgument('user', $user);
		$this->addArgument('pass', sha1($password));
		$json	= $this->send();
		if($json == false)
			return false;
		return new Profile($json);
	}
	public /*Profile|false*/ function read(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::read();
		$this->addArgument('id', $id);
		$json	= $this->send();
		if($json == false)
			return false;
		return new Profile($json);
	}
	public /*void*/ function delete(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::delete();
		$this->addArgument('id', $id);
		$this->send();
	}
}
?>