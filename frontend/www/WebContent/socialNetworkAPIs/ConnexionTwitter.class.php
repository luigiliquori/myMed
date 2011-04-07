<?php
require_once dirname(__FILE__).'/twitter/lib/twitteroauth/twitteroauth/twitteroauth.php';
require_once dirname(__FILE__).'/twitter/config.php';
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
		elseif($twitter_user = $this->connect())
		{
			$_SESSION['user'] = array(
					'id'				=> $twitter_user->id_str,
					'name'				=> $twitter_user->name,
					'gender'			=> 'something',
					'locale'			=> $twitter_user->lang,
					'updated_time'		=> $twitter_user->created_at,
					'profile'			=> 'http://twitter.com/?id='.$twitter_user->id,
					'profile_picture'	=> str_replace("_normal", "", $twitter_user->profile_image_url),
					'social_network'	=> 'twitter');
			
			if(isset($_GET['oauth_token'])||isset($_GET['oauth_verifier']))
			{
				$encoded = json_encode($_SESSION['user']);
				file_get_contents(trim(BACKEND_URL."ProfileRequestHandler?act=0&user=" . urlencode($encoded)));
				// rediriger en nettoyant les variales GET
				unset($_GET['oauth_token']);
				unset($_GET['oauth_verifier']);
				$query_string	= http_build_query($_GET);
				if($query_string != "")
					$query_string = '?'.$query_string;
				header('Location:'.$_SERVER["SCRIPT_NAME"].$query_string);
				exit;
			}
		}
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
			die('Impossible de se connecter à twitter ... Merci de renouveler votre demande plus tard.');
	}
	private /*void*/ function connect()
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
			return $connection->get('account/verify_credentials');
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
				return $connection->get('account/verify_credentials');
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
		<form method="get" action="">
			<div>
				<input type="hidden" name="connexion" value="twitter" />
				<button type="submit" class="twitter"><span>Twitter</span></button>
			</div>
		</form>
<?php
	}
}
?>