<?php
require_once dirname(__FILE__).'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionYahoo extends ConnexionAutoOpenId
{
	protected $social_network = 'Yahoo !';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://yahoo.com';
	}
}
?>
