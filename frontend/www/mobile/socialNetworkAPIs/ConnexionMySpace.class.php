<?php
require_once __DIR__.'/ConnexionOAuth1_0.class.php';
/**
 * A class to define a Myspace login
 * @author blanchard
 */
class ConnexionMySpace extends ConnexionOAuth1_0
{
	private	/*OAuthConsumer*/	$oAuthConsumer	= null;
	protected /*string*/ function getSocialNetworkName()
	{
		return 'Myspace';
	}
	protected /*OAuthConsumer*/ function getOAuthConsumer()
	{
		if(!$this->oAuthConsumer)
			$this->oAuthConsumer	= new OAuthConsumer(ConnexionMySpace_KEY, ConnexionMySpace_SECRET);
		return $this->oAuthConsumer;
	}
	protected /*string*/ function getRequestTokenUrl()
	{
		return 'http://api.myspace.com/request_token';
	}
	protected /*string*/ function getAccessTokenUrl()
	{
		return 'http://api.myspace.com/access_token';
	}
	protected /*string*/ function getAuthorizeUrl()
	{
		return 'http://api.myspace.com/authorize';
	}
	protected /*void*/ function createProfileAndFriendsInstances()
	{
		$user		= json_decode($this->httpSignedGet('http://api.myspace.com/v1/user.json'));
		$profile	= json_decode($this->httpSignedGet('http://api.myspace.com/v1/users/'.$user->userId.'/profile.json'));
		$profile2	= json_decode($this->httpSignedGet('http://api.myspace.com/1.0/people/@me/@self'));
		$friends	= json_decode($this->httpSignedGet('http://api.myspace.com/1.0/people/@me/@friends'), true);
		//var_dump($user);
		//var_dump($profile);
		//var_dump($profile2);
		//var_dump($friends['entry']);exit;
		$_SESSION['user'] = new Profile;
		$_SESSION['user']->id				= 'Myspace'.$profile->basicprofile->userId;
		$_SESSION['user']->socialNetworkID		= $profile->basicprofile->userId;
		$_SESSION['user']->socialNetworkName	= 'Myspace';
		$_SESSION['user']->name					= $profile->basicprofile->name;
		$_SESSION['user']->firstName			= $profile2->person->name->givenName;
		$_SESSION['user']->lastName				= $profile2->person->name->familyName;
		$_SESSION['user']->link					= $profile->basicprofile->webUri;
		$_SESSION['user']->hometown				= $profile->hometown;
		$_SESSION['user']->gender				= $profile->gender=='Male'?'M':($profile->gender=='Female'?'F':null);
		$_SESSION['user']->profilePicture		= $profile->basicprofile->largeImage;
		$_SESSION['friends']	= Array();
		foreach($friends['entry'] as $profile)
			$_SESSION['friends'][]	= $profile['person'];
		//var_dump($_SESSION['user']);exit;
	}
}
?>
