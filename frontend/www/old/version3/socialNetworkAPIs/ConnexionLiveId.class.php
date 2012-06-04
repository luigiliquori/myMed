<?php
require_once __DIR__.'/ConnexionOAuth2_0.class.php';
/**
 * A class to define a LiveId login to myMed
 * see parameters at http://msdn.microsoft.com/en-us/library/hh243648.aspx
 * @author blanchard
 */
class ConnexionLiveId extends ConnexionOAuth2_0
{
	protected	/*string*/	function getSocialNetwork(){return 'Live ID';}
	protected	/*string*/	function getProviderAuthorizeUrl(){return 'https://oauth.live.com/authorize';}
	protected	/*string*/	function getProviderReqTokenUrl(){return 'https://oauth.live.com/token';}
	protected	/*string*/	function getOAuthConsumerId(){return ConnexionLiveId_KEY;}
	protected	/*string*/	function getOAuthConsumerSecret(){return ConnexionLiveId_SECRET;}
	protected /*array<string>*/ function getScope()
	{
		return Array('wl.basic','wl.birthday','wl.postal_addresses');
	}
	protected /*void*/ function getDatas($access_token)
	{
		$user					= json_decode(file_get_contents('https://apis.live.net/v5.0/me?access_token='.$access_token));
		$_SESSION['friends']	= json_decode(file_get_contents('https://apis.live.net/v5.0/me/friends?access_token='.$access_token), true);
		/*
		var_dump($user);
		var_dump($friends);
		exit;//*/
		$_SESSION['user'] = new Profile;
		$_SESSION['user']->id			= static::getSocialNetwork().$user->id;
		$_SESSION['user']->socialNetworkID	= $user->id;
		$_SESSION['user']->socialNetworkName= static::getSocialNetwork();
		$_SESSION['user']->name				= $user->name;
		$_SESSION['user']->lastName			= $user->last_name;
		$_SESSION['user']->firstName		= $user->first_name;
		$_SESSION['user']->gender			= $user->gender=='male'?'M':($user->gender=='female'?'F':null);
		$_SESSION['user']->birthday			= $user->birth_year&&$user->birth_month&&$user->birth_day?sprintf('%04d-%02d-%02d', $user->birth_year, $user->birth_month, $user->birth_day):null;
		$_SESSION['user']->link				= $user->link;
		$_SESSION['user']->hometown			= $user->addresses->personal->city;
		//$_SESSION['user']->profilePicture	= 
		
		$_SESSION['friends']	= $_SESSION['friends']['data'];
		$length	= count($_SESSION['friends']);
		for($i=0 ; $i<$length ; $i++)
		{
			$_SESSION['friends'][$i]['profileUrl']	= 'http://cid-'.$_SESSION['friends'][$i]['id'].'.profile.live.com/';
			$_SESSION['friends'][$i]['displayName']	= $_SESSION['friends'][$i]['name'];
		}
	}
}
?>
