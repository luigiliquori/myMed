<?php
require_once __DIR__.'/AbstractBackendRequest.class.php';
require_once __DIR__.'/model/Profile.class.php';
require_once __DIR__.'/model/Authentication.class.php';
class AuthenticationRequest extends AbstractBackendRequest
{
	public /*Profile*/ function create(Authentication $authentication, Profile $user) /*throws BackendRequestException, CUrlException*/
	{
		parent::create();
		trace($user, '$user');
		trace($authentication, '$profile');
		printTraces();
		$this->addArgument('authentication', $authentication);
		$this->addArgument('user', $user);
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
		$this->addArgument('user', $authentication);
		$this->send();
	}
}
?>
