<?php
require_once __DIR__.'/Connexion.class.php';
require_once __DIR__.'/../system/backend/ProfileRequest.class.php';
/**
 * A class to define an anonymous login
 * @author blanchard
 */
class ConnexionGuest extends Connexion
{
	public function __construct()
	{
		if(isset($_GET["connexion"])&&$_GET["connexion"]=='guest')
		{
			$_SESSION['user'] = new Profile;
			header('Location:'.$_SERVER['PHP_SELF']);
			exit;
		}
	}
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?><a href="?connexion=guest" class="guest"><span>Visiteur</span></a><?php
	}
}
?>
