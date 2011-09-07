<?php
require_once __DIR__.'/Connexion.class.php';
require_once __DIR__.'/ConnexionFacebook.class.php';
require_once __DIR__.'/ConnexionTwitter.class.php';
require_once __DIR__.'/ConnexionGoogle.class.php';
/**
 * a class to initialize all Connexions class
 * @author Bastien BLANCHARD
 */
class GlobalConnexion extends Connexion
{
	private /*array<Connexion>*/ $connexions = array();
	private /*array<Connexion>*/ $mainConnexions = array();
	private /*array<Connexion>*/ $minorConnexions = array();
	private static /*GlobalConnexion*/ $instance = null;
	
	private function __construct()
	{
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionFacebook;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionGoogle;
		$this->connexions[] = $this->mainConnexions[]	= new ConnexionTwitter;
		if(isset($_POST["logout"]))
		{
			session_destroy();
			httpRedirect($_SERVER["REQUEST_URI"]);
		}
	}
	
	/**
	 * Print content's tags to be put inside \<head\> tag
	 */
	public /*void*/ function headTags()
	{
		foreach($this->connexions as $connexion)
			$connexion->headTags();
	}
	
	/**
	 * Print content's tags to be put at the end of the xHtml document. Usefull fo JavaScript Initilizations
	 */
	public /*void*/ function scriptTags()
	{
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
	 * Get the unique instance of GlobalConnexion
	 * Instance it if never called
	 */
	public static /*GlobalConnexion*/ function getInstance()
	{
		if(self::$instance == null)
			self::$instance = new GlobalConnexion();
		return self::$instance;
	}
}
?>
