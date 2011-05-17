<?php
require_once dirname(__FILE__).'/AbstractBackendRequest.class.php';
require_once dirname(__FILE__).'/Profile.class.php';
///@todo gérer les erreurs 500/404
class ProfileRequest extends AbstractBackendRequest
{
	public /*Profile*/ function create(Profile $user)
	{
		parent::create();
		$this->addArgument('user', json_encode($user));
		$json	= $this->send();
		return Profile::__set_state(json_decode($json, true));
	}
	public /*Profile*/ function read(/*string*/ $id, /*string*/ $socialNetwork)
	{
		parent::read();
		$this->addArgument('id', $id);
		$this->addArgument('social_network', $socialNetwork);
		$json	= $this->send();
		return Profile::__set_state(json_decode($json, true));
	}
	public /*void*/ function update(Profile $user)
	{
		parent::update();
		$this->addArgument('user', json_encode($user));
		$this->send();
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