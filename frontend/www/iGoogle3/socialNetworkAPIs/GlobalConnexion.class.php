<?php
require_once dirname(__FILE__).'/Connexion.class.php';
require_once dirname(__FILE__).'/ConnexionGuest.class.php';
require_once dirname(__FILE__).'/ConnexionMyMed.class.php';
require_once dirname(__FILE__).'/ConnexionFacebook.class.php';
require_once dirname(__FILE__).'/ConnexionTwitter.class.php';
require_once dirname(__FILE__).'/ConnexionOpenId.class.php';
require_once dirname(__FILE__).'/ConnexionGoogle.class.php';
require_once dirname(__FILE__).'/ConnexionYahoo.class.php';
require_once dirname(__FILE__).'/ConnexionOrange.class.php';
require_once dirname(__FILE__).'/ConnexionMyOpenId.class.php';
require_once dirname(__FILE__).'/ConnexionWordPress.class.php';
require_once dirname(__FILE__).'/ConnexionVeriSign.class.php';
require_once dirname(__FILE__).'/ConnexionSteam.class.php';
require_once dirname(__FILE__).'/ConnexionAol.class.php';
require_once dirname(__FILE__).'/ConnexionLiveId.class.php';
require_once dirname(__FILE__).'/ConnexionMySpace.class.php';
abstract class GlobalConnexion extends Connexion
{
	private /*array<Connexion>*/ $connexions = array();
	private /*array<Connexion>*/ $mainConnexions = array();
	private /*array<Connexion>*/ $minorConnexions = array();
	private /*Connexion*/ $guestConnexion;
	public function __construct()
	{
		$this->guestConnexion = new ConnexionGuest;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionAol;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionFacebook;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionGoogle;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionLiveId;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionMyMed;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionMyOpenId;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionMySpace;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionOpenId;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionOrange;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionSteam;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionTwitter;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionVeriSign;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionWordPress;
		$this->connexions[] = $this->minorConnexions[]	= new ConnexionYahoo;
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
		echo '<ul>';
		foreach($this->connexions as $connexion)
		{
			echo '<li>';
			$connexion->button();
			echo '</li>';
		}
		echo '</ul>';
	}
	/**
	 * Print the main connexions's buttons
	 */
	public /*void*/ function mainButtons()
	{
		echo '<ul>';
		foreach($this->mainConnexions as $connexion)
		{
			echo '<li>';
			$connexion->button();
			echo '</li>';
		}
		echo '</ul>';
	}
	/**
	 * Print the minor connexions's buttons
	 */
	public /*void*/ function minorButtons()
	{
		echo '<ul>';
		foreach($this->minorConnexions as $connexion)
		{
			echo '<li>';
			$connexion->button();
			echo '</li>';
		}
		echo '</ul>';
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
