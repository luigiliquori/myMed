<?php
require_once __DIR__.'/Connexion.class.php';
/**
 * A class to define a OAuth 2.0 login
 * @author blanchard
 */
abstract class ConnexionOAuth2_0 extends Connexion
{
	/**
	 * get API'spermission requested
	 * @return array<string>	the list of permission requested (OAuth's scope variable)
	 */
	protected abstract	/*array<string>*/	function getScope();
	/**
	 * Get social network name
	 * @return string	social network name
	 */
	protected abstract	/*string*/	function getSocialNetwork();
	/**
	 * get The URL where the user is redirected for authentication
	 * @return string	autorize's URL
	 */
	protected abstract	/*string*/	function getProviderAuthorizeUrl();
	/**
	 * get The URL where request_token must be requested
	 * @return string	the request_token's URL
	 */
	protected abstract	/*string*/	function getProviderReqTokenUrl();
	/**
	 * Get consumer's ID
	 * @return string	Consumer's Id
	 */
	protected abstract	/*string*/	function getOAuthConsumerId();
	/**
	 * Get consumer's secret
	 * @return string	Consumer's secret
	 */
	protected abstract	/*string*/	function getOAuthConsumerSecret();
	public function __construct()
	{
		if(isset($_GET['connexion'])&&$_GET['connexion']==static::getSocialNetwork()) // si l'utilisateur a cliqué sur le bouton twitter
			$this->redirect();
		elseif(!USER_CONNECTED && isset($_GET['state']) && preg_replace('#\\?.*$#','',$_GET['state'])==static::getSocialNetwork() && isset($_GET['code']))
		{
			$answer	= json_decode(file_get_contents(static::getProviderReqTokenUrl().
					'?grant_type=authorization_code'.
					'&client_id='.static::getOAuthConsumerId().
					'&client_secret='.static::getOAuthConsumerSecret().
					'&code='.$_GET['code'].
					'&redirect_uri='.urlencode($this->getCallBackUrl())));
			//var_dump($answer);exit;
			if(!$answer)
				sendError('Erreur de Connexion', true);
			static::getDatas($answer->access_token);
			$this->redirectAfterConnection();
		}
	}
	/**
	 * Function called by redirectAfterConnection() to remove GET variables from URL of redirection
	 */
	protected /*void*/ function cleanGetVars()
	{
		$query	= preg_replace('#^[^?]*\?#', '', $_GET['state']);
		unset($_GET['code']);
		unset($_GET['state']);
		parse_str($query, $_GET);
	}
	/**
	 * Redirect to provider for user authentication
	 */
	private /*void*/ function redirect()
	{
		$arr_scope	= static::getScope();
		$str_scope	= '';
		$length		= count($arr_scope);
		if($length>0)
		foreach($arr_scope as $elem)
		{
			$str_scope .= $elem;
			if($length>1)
				$str_scope .= ',';
			$length--;
		}
		
		// to keep GET vars state, clean parasit vars
		$get	= $_GET;
		unset($get['connexion']);
		$query_string	= http_build_query($get);
		if($query_string != "")
			$query_string = '?'.$query_string;
		
		httpRedirect(static::getProviderAuthorizeUrl().
				'?client_id='.static::getOAuthConsumerId().
				'&scope='.$str_scope.
				'&response_type=code'.
				'&state='.urlencode(static::getSocialNetwork().$query_string).
				'&redirect_uri='.urlencode($this->getCallBackUrl()));
	}
	/**
	 * Create Callback URL from current URL
	 * @return string	callback url
	 */
	private /*string*/ function getCallBackUrl()
	{
		return 'https://'.$_SERVER['HTTP_HOST'].preg_replace('#\\?.*$#', '', $_SERVER['REQUEST_URI']);
	}
	public /*void*/ function button()
	{
		// remove parasit GET vars
		$get	= $_GET;
		unset($get['connexion']);
		$query_string	= http_build_query($get);
		if($query_string != "")
			$query_string = '&'.$query_string;
?><a rel="external" href="?connexion=<?=urlencode(static::getSocialNetwork()).htmlspecialchars($query_string)?>" class="<?=preg_replace('#[^a-z0-9]#','',strtolower(static::getSocialNetwork()))?>"><span><?=static::getSocialNetwork()?></span></a><?php
	}
}
?>