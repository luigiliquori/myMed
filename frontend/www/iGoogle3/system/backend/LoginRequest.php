<?php
require_once dirname(__FILE__).'/AbstractBackendRequest.class.php';
require_once dirname(__FILE__).'/Profile.class.php';
///@todo gérer les erreurs 500/404
class LoginRequest extends AbstractBackendRequest
{
	public /*Profile|false*/ function create(/*string*/ $login, /*string*/ $password)
	{
		parent::create();
		$this->addArgument('user', $user);
		$this->addArgument('pass', sha1($password));
		$json	= $this->send();
		if($json == false)
			return false;
		return Profile::__set_state(json_decode($json, true));
	}
	public /*Profile|false*/ function read(/*string*/ $id, /*string*/ $socialNetwork)
	{
		parent::read();
		$this->addArgument('id', $id);
		$this->addArgument('social_network', $socialNetwork);
		$json	= $this->send();
		if($json == false)
			return false;
		return Profile::__set_state(json_decode($json, true));
	}
	public /*void*/ function delete(/*string*/ $id, /*string*/ $socialNetwork)
	{
		parent::delete();
		$this->addArgument('id', $id);
		$this->addArgument('social_network', $socialNetwork);
		$this->send();
	}
}
?>