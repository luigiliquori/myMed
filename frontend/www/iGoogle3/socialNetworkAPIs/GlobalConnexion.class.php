<?php
require_once dirname(__FILE__).'/Connexion.class.php';
require_once dirname(__FILE__).'/ConnexionGuest.class.php';
require_once dirname(__FILE__).'/ConnexionMyMed.class.php';
require_once dirname(__FILE__).'/ConnexionFacebook.class.php';
require_once dirname(__FILE__).'/ConnexionTwitter.class.php';
require_once dirname(__FILE__).'/ConnexionOpenId.class.php';
require_once dirname(__FILE__).'/ConnexionGoogle.class.php';
abstract class GlobalConnexion extends Connexion
{
	private /*array<Connexion>*/ $connexions = array();
	private /*Connexion*/ $guestConnexion;
	public function __construct()
	{
		$this->guestConnexion = new ConnexionGuest;
		//$this->connexions[] = new ConnexionFacebook;
		$this->connexions[] = new ConnexionGoogle;
		$this->connexions[] = new ConnexionMyMed;
		$this->connexions[] = new ConnexionOpenId;
		$this->connexions[] = new ConnexionTwitter;
		if(isset($_POST["logout"]))
		{
			session_destroy();
			header('Location:'.$_SERVER["REQUEST_URI"]);
			exit;
		}
	}
	/**
	 * Print content's tags to be put inside <head> tag
	 */
	public /*void*/ function headTags()
	{
		$this->guestConnexion->headTags();
		foreach($this->connexions as $connexion)
			$connexion->headTags();
	}
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
		$this->guestConnexion->scriptTags();
		foreach($this->connexions as $connexion)
			$connexion->scriptTags();
	}
	/**
	 * Print the connexion's buttons
	 */
	public /*void*/ function button()
	{
		foreach($this->connexions as $connexion)
			$connexion->button();
	}
	/**
	 * Print the guest connexion's button
	 */
	public /*void*/ function guestButton()
	{
		$this->guestConnexion->button();
	}
}
?>