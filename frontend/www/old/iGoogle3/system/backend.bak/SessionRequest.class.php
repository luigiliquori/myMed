<?php
require_once __DIR__.'/AbstractBackendRequest.class.php';
require_once __DIR__.'/Session.class.php';
class SessionRequest extends AbstractBackendRequest
{
	public /*???*/ function create(/*string*/ $id, /*string*/ $ip) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		$this->addArgument('id', $id);
		$this->addArgument('pass', $ip);
		$this->send();
	}
	public /*Session*/ function read(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::read();
		$this->addArgument('id', $id);
		$json	= $this->send();
		return new Session($json);
	}
	public /*Session*/ function update(/*string*/ $id, /*string*/ $ip) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		$this->addArgument('id', $id);
		$this->addArgument('pass', $ip);
		$json	= $this->send();
		return new Session($json);
	}
	public /*???*/ function delete(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::delete();
		$this->addArgument('id', $id);
		$this->send();
	}
}
?>
