<?php
require_once dirname(__FILE__).'/twitter/twitteroauth.php';
define('CONSUMER_KEY', 'HgsnlIpCJ7RqHhCFELkTvw');
define('CONSUMER_SECRET', 'P7Gkj9AfeNEIHXrj0PMTiNHM3lJbHEqkuXwuWtGzU');
define('OAUTH_CALLBACK', 'http://'.$_SERVER['HTTP_HOST'].ROOTPATH);
/**
 * A class to define a twitter login to myMed
 * TWITTER NEED PHP5-CURL PACKAGE
 * @author blanchard
 */
class ConnexionTwitter extends Connexion
{
	public function __construct()
	{
		if(isset($_GET['connexion'])&&$_GET['connexion']=='twitter') // si l'utilisateur a cliqué sur le bouton twitter
			$this->redirect();
		elseif(!USER_CONNECTED && $connection = $this->connect())
		{
			$twitter_user	= $connection->get('account/verify_credentials');
			$img = str_replace("_normal", "", $twitter_user->profile_image_url);
			if(@file_get_contents($img, false, stream_context_create(array('http'=>array('method'=>"HEAD"))))===false)
				$img = str_replace("_normal", "_bigger", $twitter_user->profile_image_url);
			//var_dump($twitter_user);exit;
			$data	= $this->connect();
			$_SESSION['user'] = new Profile;
			$_SESSION['user']->mymedID			= 'twitter'.$twitter_user->id_str;
			$_SESSION['user']->socialNetworkID	= $twitter_user->id_str;
			$_SESSION['user']->socialNetworkName= 'twitter';
			$_SESSION['user']->name				= $twitter_user->name;
			$_SESSION['user']->link				= $twitter_user->url;
			$_SESSION['user']->link				= 'http://fr.twitter.com/'.$twitter_user->screen_name;
			$_SESSION['user']->profilePicture	= $img;
			$_SESSION['friends'] = $connection->get('http://api.twitter.com/statuses/friends.json');
			$length	= count($_SESSION['friends']);
			for($i=0 ; $i<$length ; $i++)
			{
				$_SESSION['friends'][$i]	= (array)$_SESSION['friends'][$i];
				$_SESSION['friends'][$i]['profileUrl']	= 'https://twitter.com/'.$_SESSION['friends'][$i]['screen_name'];
				$_SESSION['friends'][$i]['displayName']	= $_SESSION['friends'][$i]['screen_name'];
			}
			$this->redirectAfterConnection();
		}
	}
	protected /*void*/ function cleanGetVars()
	{
		unset($_GET['oauth_token']);
		unset($_GET['oauth_verifier']);
	}
	private /*void*/ function redirect()
	{
		/* Créer une connexion twitter avec les accès de l'application */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		
		/* On demande les tokens à Twitter, et on passe l'URL de callback */
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
		
		/* On sauvegarde le tout en session */
		$_SESSION['oauth_token']		= $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		/* On test le code de retour HTTP pour voir si la requête précédente a correctement fonctionné */
		if ($connection->http_code==200)
		{
			/* On construit l'URL de callback avec les tokens en params GET */
			$url = $connection->getAuthorizeURL($_SESSION['oauth_token']);
			header('Location: ' . $url);
			exit;
		}
		else
			sendError('Impossible de se connecter à twitter... Merci de renouveler votre demande plus tard.', true);
	}
	private /*TwitterOAuth*/ function connect()
	{
		if(
				!empty($_SESSION['access_token']) 
			&&	!empty($_SESSION['access_token']['oauth_token'])
			&&	!empty($_SESSION['access_token']['oauth_token_secret']))
		{
			// On a les tokens d'accès, l'authentification est OK.
 
			/* On créé la connexion avec twitter en donnant les tokens d'accès en paramètres.*/
			$connection = new TwitterOAuth(
					CONSUMER_KEY, 
					CONSUMER_SECRET, 
					$_SESSION['access_token']['oauth_token'], 
					$_SESSION['access_token']['oauth_token_secret']);
 
			/* On récupère les informations sur le compte twitter du visiteur */
			return $connection;
		}
		elseif(
				isset($_REQUEST['oauth_token'], $_SESSION['oauth_token'])
				&&	$_SESSION['oauth_token'] === $_REQUEST['oauth_token'])
		{
			// Les tokens d'accès ne sont pas encore stockés, il faut vérifier l'authentification
 
			/* On créé la connexion avec twitter en donnant les tokens d'accès en paramètres.*/
			$connection = new TwitterOAuth(
					CONSUMER_KEY, 
					CONSUMER_SECRET, 
					$_SESSION['oauth_token'], 
					$_SESSION['oauth_token_secret']);
 
					/* On vérifie les tokens et récupère le token d'accès */
					$_SESSION['access_token'] = $connection->getAccessToken($_REQUEST['oauth_verifier']);
					
					unset($_SESSION['oauth_token']);
					unset($_SESSION['oauth_token_secret']);
 
			if (200 == $connection->http_code)
				return $connection;
		}
		return null;
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags(){}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags(){}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?>
		<a href="?connexion=twitter" class="twitter"><span>Twitter</span></a>
<?php
	}
}
?>
