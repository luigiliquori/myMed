<?php
require_once dirname(__FILE__).'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionAol extends ConnexionAutoOpenId
{
	protected $social_network = 'Aol';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://openid.aol.com';
	}
}
?>