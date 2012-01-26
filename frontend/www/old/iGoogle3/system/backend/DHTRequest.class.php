<?php
require_once __DIR__.'/AbstractBackendRequest.class.php';
class DHTRequest extends AbstractBackendRequest
{
	public /*void*/ function create(/*string*/ $key, /*string*/ $value) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		$this->addArgument('key', $key);
		$this->addArgument('value', $value);
		$this->send();
	}
	public /*string*/ function read(/*string*/ $key) /*throws BackendRequestException, CUrlException*/
	{
		parent::read();
		$this->addArgument('key', $key);
		return $this->send();
	}
	public /*void*/ function update(/*string*/ $key, /*string*/ $value) /*throws BackendRequestException, CUrlException*/
	{
		parent::update();
		$this->addArgument('key', $key);
		$this->addArgument('value', $value);
		$this->send();
	}
	public /*void*/ function delete(/*string*/ $key) /*throws BackendRequestException, CUrlException*/
	{
		parent::delete();
		$this->addArgument('key', $key);
		$this->send();
	}
}
?>