<?php
require_once dirname(__FILE__).'/ConnexionOpenId.class.php';
/**
 * A class to define a myMed login
 * @author blanchard
 */
class ConnexionMyMed extends ConnexionOpenId
{
	protected $social_network = 'myMed';
	public function __construct()
	{
		if(!isset($_REQUEST['connexionProvider'])
			||$_REQUEST['connexionProvider']=='myMed'
			||$this->social_network!='myMed')
		{
			$this->consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore('/tmp/oid_store'));
			static::tryConnect();
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?>
		<a href="?connexion=openid&connexionProvider=myMed&uri=http://<?=$_SERVER["HTTP_HOST"].ROOTPATH?>openid.php" class="mymed"><span>MyMed</span></a>
<?php
	}
}
?>