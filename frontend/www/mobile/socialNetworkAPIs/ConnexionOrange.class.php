<?php
require_once __DIR__.'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a Orange login
 * @author blanchard
 */
class ConnexionOrange extends ConnexionAutoOpenId
{
	protected $social_network = 'Orange';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 * Get provider's URL to be used by OpenID Authentification
	 * @return string	Provider's URL
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://orange.fr';
	}
}
?>
