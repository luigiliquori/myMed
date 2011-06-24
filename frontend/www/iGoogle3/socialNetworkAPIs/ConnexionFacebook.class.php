<?php
require_once dirname(__FILE__).'/facebook/facebook.php';
define('FACEBOOK_APP_ID','154730914571286');
/**
 * A class to define a facebook login to myMed
 * @author blanchard
 */
class ConnexionFacebook extends Connexion
{
	private /*Facebook*/ $facebook;
	public function __construct()
	{
		$this->facebook = new Facebook(Array(
				'appId'		=> '154730914571286',
				'secret'	=> 'a29bd2d27e8beb0b34da460fcf7b5522'));
		$cookie = $this->facebook->getSession();
		if ($cookie['access_token'] && !USER_CONNECTED)
		{
			$facebook_user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
	trace($facebook_user);
		
			$_SESSION['user'] = array(
					'id'				=> $facebook_user->id,
					'name'				=> $facebook_user->name,
					'gender'			=> $facebook_user->gender,
					'locale'			=> $facebook_user->locale,
					'updated_time'		=> $facebook_user->updated_time,
					'profile'			=> 'http://www.facebook.com/profile.php?id='.$facebook_user->id,
					'profile_picture'	=> 'http://graph.facebook.com/'.$facebook_user->id.'/picture?type=large',
					'social_network'	=> 'facebook');
			$_SESSION['friends'] = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$cookie['access_token']), true);
			$_SESSION['friends']	= $_SESSION['friends']['data'];
			$length	= count($_SESSION['friends']);
			for($i=0 ; $i<$length ; $i++)
			{
				$_SESSION['friends'][$i]['profileUrl']	= 'http://www.facebook.com/#!/profile.php?id='.$_SESSION['friends'][$i]['id'];
				$_SESSION['friends'][$i]['displayName']	= $_SESSION['friends'][$i]['name'];
			}
			$encoded = json_encode($_SESSION['user']);
			file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));
			header('Location:'.$_SERVER["SCRIPT_NAME"]);
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		$url = $this->facebook->getLoginUrl();
?><a href="<?php echo htmlspecialchars($url);?>" class="facebook"><span>Facebook</span></a><?php
	}
}
?>
