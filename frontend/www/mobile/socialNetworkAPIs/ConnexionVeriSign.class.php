<?php
require_once __DIR__.'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a VeriSign(Symantec)) login
 * @author blanchard
 */
class ConnexionVeriSign extends ConnexionAutoOpenId
{
	protected $social_network = 'VeriSign';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 * Get provider's URL to be used by OpenID Authentification
	 * @return string	Provider's URL
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'https://pip.verisignlabs.com';
	}
}
?>
