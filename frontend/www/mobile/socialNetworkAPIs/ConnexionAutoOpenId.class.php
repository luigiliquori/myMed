<?php
require_once __DIR__.'/ConnexionOpenId.class.php';
/**
 * A class to define a OpenId login with a simple button
 * @author blanchard
 */
abstract class ConnexionAutoOpenId extends ConnexionOpenId
{
	public function __construct()
	{
		if(isset($_REQUEST['connexionProvider'])&&$_REQUEST['connexionProvider']==$this->social_network)
		{
			$this->consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore('/tmp/oid_store'));
			static::tryConnect();
		}
	}
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 * Get provider's URL to be used by OpenID Authentification
	 * @return string	Provider's URL
	 */
	public abstract /*string*/ function getProviderUrl();
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
		// remove parasit GET vars
		$get	= $_GET;
		unset($get['connexion']);
		unset($get['connexionProvider']);
		unset($get['oidUri']);
		$query_string	= http_build_query($get);
		if($query_string != "")
			$query_string = '&'.$query_string;
?><a rel="external" href="?connexion=openid&amp;connexionProvider=<?=urlencode($this->social_network)?>&amp;oidUri=<?=static::getProviderUrl()?><?=htmlspecialchars($query_string)?>" class="<?=preg_replace('#[^a-z0-9]#','',strtolower($this->social_network))?>"><span><?= $this->social_network?></span></a><?php
	}
}
?>
