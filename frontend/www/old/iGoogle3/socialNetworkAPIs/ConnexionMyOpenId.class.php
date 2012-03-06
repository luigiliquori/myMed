<?php
require_once __DIR__.'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionMyOpenId extends ConnexionAutoOpenId
{
	protected $social_network = 'myOpenId';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://myopenid.com';
	}
}
?>
