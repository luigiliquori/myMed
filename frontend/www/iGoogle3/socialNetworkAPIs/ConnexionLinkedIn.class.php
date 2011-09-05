<?php
require_once __DIR__.'/ConnexionOAuth1_0.class.php';
/**
 * A class to define a Myspace login
 * @author blanchard
 */
class ConnexionLinkedIn extends ConnexionOAuth1_0
{
	private	/*OAuthConsumer*/	$oAuthConsumer	= null;
	protected /*string*/ function getSocialNetworkName()
	{
		return 'LinkedIn';
	}
	protected /*OAuthConsumer*/ function getOAuthConsumer()
	{
		if(!$this->oAuthConsumer)
			$this->oAuthConsumer	= new OAuthConsumer(ConnexionLinkedIn_KEY, ConnexionLinkedIn_SECRET);
		return $this->oAuthConsumer;
	}
	protected /*string*/ function getRequestTokenUrl()
	{
		return 'https://api.linkedin.com/uas/oauth/requestToken';
	}
	protected /*string*/ function getAccessTokenUrl()
	{
		return 'https://api.linkedin.com/uas/oauth/accessToken';
	}
	protected /*string*/ function getAuthorizeUrl()
	{
		return 'https://api.linkedin.com/uas/oauth/authenticate';
	}
	protected /*void*/ function createProfileAndFriendsInstances()
	{
		sendError('LinkedIn Not Yet Availaible');
		$user		= json_decode($this->httpSignedGet('http://api.linkedin.com/v1/user.json'));
		$profile	= json_decode($this->httpSignedGet('http://api.linkedin.com/v1/users/'.$user->userId.'/profile.json'));
		$profile2	= json_decode($this->httpSignedGet('http://api.linkedin.com/v1/people/@me/@self'));
		$friends	= json_decode($this->httpSignedGet('http://api.linkedin.com/v1/people/@me/@friends'), true);
		$_SESSION['user'] = new Profile;
		$_SESSION['user']->id				= 'LinkedIn'.$profile->basicprofile->userId;
		$_SESSION['user']->socialNetworkID		= $profile->basicprofile->userId;
		$_SESSION['user']->socialNetworkName	= 'LinkedIn';
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
	}
}
?>
