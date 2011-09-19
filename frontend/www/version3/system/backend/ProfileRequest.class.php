<?php
require_once __DIR__.'/AbstractBackendRequest.class.php';
require_once __DIR__.'/model/Profile.class.php';
class ProfileRequest extends AbstractBackendRequest
{
	public /*Profile*/ function create(Profile $user) /*throws BackendRequestException, CUrlException*/
	{var_dump($user);
		parent::create();
		$this->addArgument('user', $user);
		$json	= $this->send();
		return new Profile($json);
	}
	public /*Profile*/ function read(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::read();
		$this->addArgument('id', $id);
		$json	= $this->send();
		return new Profile($json);
	}
	public /*void*/ function update(Profile $user) /*throws BackendRequestException, CUrlException*/
	{
		parent::update();
		$this->addArgument('user', $user);
		$this->send();
	}
	public /*void*/ function delete(/*string*/ $id) /*throws BackendRequestException, CUrlException*/
	{
		parent::delete();
		$this->addArgument('id', $id);
		$this->send();
	}
}
?>