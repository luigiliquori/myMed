<?php
require_once __DIR__.'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a Aol login
 * @author blanchard
 */
class ConnexionAol extends ConnexionAutoOpenId
{
	protected $social_network = 'Aol';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 * Get provider's URL to be used by OpenID Authentification
	 * @return string	Provider's URL
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://openid.aol.com';
	}
}
?>