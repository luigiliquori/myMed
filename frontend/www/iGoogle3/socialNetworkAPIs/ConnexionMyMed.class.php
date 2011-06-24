<?php
require_once dirname(__FILE__).'/ConnexionAutoOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionMyMed extends ConnexionAutoOpenId
{
	protected $social_network = 'myMed';
	/**
	 * Récupère l'URL à appeler par OpenId pour la connexion
	 */
	public /*string*/ function getProviderUrl()
	{
		return 'http://'.$_SERVER["HTTP_HOST"].ROOTPATH.'openid';
	}
}
?>
