<?php
require_once dirname(__FILE__).'/facebook/facebook.php';
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
				'secret'	=> '06b728cd7b6527c7cc2af70b3581bbf3'));
		$cookie = $this->facebook->getSession();
		if ($cookie['access_token'] && !$_SESSION['logged'])
		{
			$facebook_user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
		
			$_SESSION['user'] = array(
					'id'				=> $facebook_user->id,
					'name'				=> $facebook_user->name,
					'gender'			=> $facebook_user->gender,
					'locale'			=> $facebook_user->locale,
					'updated_time'		=> $facebook_user->updated_time,
					'profile'			=> 'http://www.facebook.com/profile.php?id='.$facebook_user->id,
					'profile_picture'	=> 'http://graph.facebook.com/'.$facebook_user->id.'/picture?type=large',
					'social_network'	=> 'facebook');
			$_SESSION['friends'] = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$cookie['access_token']))->data;
			$encoded = json_encode($_SESSION['user']);
			file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		$url = $this->facebook->getLoginUrl();
?>
		<form method="get" action="<?php echo htmlspecialchars($url);?>">
			<div>
				<input type="hidden" name="connexion" value="facebook" />
				<button type="submit" class="facebook"><span>Facebook</span></button>
			</div>
		</form>
<?php
	}
}
?>