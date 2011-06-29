<?php
require_once dirname(__FILE__).'/ConnexionOAuth1_0.class.php';
define('CONSUMER_KEY', 'HgsnlIpCJ7RqHhCFELkTvw');
define('CONSUMER_SECRET', 'P7Gkj9AfeNEIHXrj0PMTiNHM3lJbHEqkuXwuWtGzU');
/**
 * A class to define a twitter login to myMed
 * TWITTER NEED PHP5-CURL PACKAGE
 * @author blanchard
 */
class ConnexionTwitter extends ConnexionOAuth1_0
{
	private	/*OAuthConsumer*/	$oAuthConsumer	= null;
	protected /*string*/ function getSocialNetworkName()
	{
		return 'Twitter';
	}
	protected /*OAuthConsumer*/ function getOAuthConsumer()
	{
		if(!$this->oAuthConsumer)
			$this->oAuthConsumer	= new OAuthConsumer('HgsnlIpCJ7RqHhCFELkTvw', 'P7Gkj9AfeNEIHXrj0PMTiNHM3lJbHEqkuXwuWtGzU');
		return $this->oAuthConsumer;
	}
	protected /*string*/ function getRequestTokenUrl()
	{
		return 'https://api.twitter.com/oauth/request_token';
	}
	protected /*string*/ function getAccessTokenUrl()
	{
		return 'https://api.twitter.com/oauth/access_token';
	}
	protected /*string*/ function getAuthorizeUrl()
	{
		return 'https://twitter.com/oauth/authenticate';
	}
	protected /*void*/ function createProfileAndFriendsInstances()
	{
		$user		= json_decode($this->httpSignedGet('https://api.twitter.com/1/account/verify_credentials.json'));
		$img = str_replace("_normal", "", $user->profile_image_url);
		if(@file_get_contents($img, false, stream_context_create(array('http'=>array('method'=>"HEAD"))))===false)
			$img = str_replace("_normal", "_bigger", $user->profile_image_url);
		$friends		= json_decode($this->httpSignedGet('https://api.twitter.com/1/friends/ids.json', Array('user_id' => $user->id_str, 'cursor'=> -1)));
		//var_dump($twitter_user);exit;
		$_SESSION['user'] = new Profile;
		$_SESSION['user']->mymedID			= 'twitter'.$user->id_str;
		$_SESSION['user']->socialNetworkID	= $user->id_str;
		$_SESSION['user']->socialNetworkName= 'twitter';
		$_SESSION['user']->name				= $user->name;
		$_SESSION['user']->link				= $user->url;
		$_SESSION['user']->profilePicture	= $img;
		$_SESSION['friends']	= Array();
		foreach($friends->ids as $id)
		{
			$obj_friend	= json_decode($this->httpSignedGet('https://api.twitter.com/1/users/show.json', Array('user_id' => $id)));
			$arr_friend	= Array();
			$arr_friend['profileUrl']	= $obj_friend->url;
			$arr_friend['displayName']	= $obj_friend->name;
			$_SESSION['friends'][]	= $arr_friend;
		}
	}
}
?>
