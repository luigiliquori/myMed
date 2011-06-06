<?php
//require_once dirname(__FILE__).'/facebook/facebook.php';
require_once dirname(__FILE__).'/../system/backend/ProfileRequest.class.php';
/**
 * A class to define a facebook login to myMed
 * @author blanchard
 */
class ConnexionFacebook extends Connexion
{
	private /*string*/ $appId = '154730914571286';
	private /*string*/ $secret = '08c119027aa665033fdb82fe3d1c56a1';
	public function __construct()
	{
		if(isset($_GET["code"], $_GET['state'])&&$_GET["code"]!='')
		{
			if($_GET['state'] != $_SESSION['state'])
				trigger_error('The state does not match. You may be a victim of CSRF.', E_USER_ERROR);
			$params = null;
			parse_str(
					file_get_contents(''), 
					$params);
		}
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
	private /*void*/ function updateProfileObject(Profile &$profile, stdClass $facebook_user)
	{
		$profile->mymedID			= 'Facebook'.$facebook_user->id;
		$profile->socialNetworkID	= $facebook_user->id;
		$profile->socialNetworkName	= 'Facebook';
		$profile->name				= $facebook_user->name;
		$profile->firstName			= $facebook_user->first_name;
		$profile->lastName			= $facebook_user->last_name;
		$profile->link				= $facebook_user->link;
		exit;
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		if(!isset($_SESSION['facebook_state']))
			$_SESSION['facebook_state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$url = 'https://www.facebook.com/dialog/oauth'
					.'?client_id='.$this->app_id
					.'&redirect_uri='.urlencode('http://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'])
					.'&state='.$_SESSION['facebook_state'];
?>
		<a href="<?php echo htmlspecialchars($url);?>" class="facebook"><span>Facebook</span></a>
<?php
	}
}
?>