<?php
require_once __DIR__.'/Connexion.class.php';
require_once __DIR__.'/../system/backend/ProfileRequest.class.php';
/**
 * A class to define an anonymous login
 * @author blanchard
 */
class ConnexionGuest extends Connexion
{
	/**
	 * Print the connexion's button
	 */
	public /*void*/ function button()
	{
?><a href="" class="guest"><span>Visiteur</span></a><?php
	}
}
?>
