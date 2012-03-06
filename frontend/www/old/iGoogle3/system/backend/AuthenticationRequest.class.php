<?php
require_once __DIR__.'/AbstractBackendRequest.class.php';
require_once __DIR__.'/Profile.class.php';
require_once __DIR__.'/Authentication.class.php';
class AuthenticationRequest extends AbstractBackendRequest
{
	public /*Profile*/ function create(Authentication $authentication, Profile $profile) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		$this->addArgument('authentication', $authentication);
		$this->addArgument('profile', $profile);
		$this->send();
	}
	public /*Profile*/ function read(/*string*/ $login, /*string*/ $password) /*throws BackendRequestException, CUrlException*/
	{
		parent::read();
		$this->addArgument('login', $login);
		$this->addArgument('password', hash('sha512', $password));
		$json	= $this->send();
		return new Profile($json);
	}
	public /*void*/ function update(Authentication $authentication) /*throws BackendRequestException, CUrlException*/
	{
		parent::update();
		$this->addArgument('authentication', $authentication);
		$this->send();
	}
}
?>
