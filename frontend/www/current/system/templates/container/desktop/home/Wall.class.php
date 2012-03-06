<?php
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Wall extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attribute */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("wall", "wall");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div>
			<h1>myMed's home page: <?= $_SESSION['user']->name ?></h1>
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		?>
		<!-- CONTENT -->
		<div style="position: absolute; top:120px; left:30%; width:40%; height:100%; border: thin #d0d0d0 solid; background-color:white; padding:5px; z-index: 99;">
			
			<!-- INSCRIPTION -->
			<div id="inscription" data-theme="d"  style="display: none;">
				<div data-role="header" data-theme="a" >
					<h2>Mise à jour de votre profile</h2>
				</div>
				<form action="#" method="post" name="inscriptionForm" id="inscriptionForm">
					<input type="hidden" name="update" value="1" />
					<span>Prénom : </span><br /><input type="text" name="prenom" value="<?= $_SESSION['user']->firstName ?>" /><br />
					<span>Nom : </span><br /><input type="text" name="nom" value="<?= $_SESSION['user']->lastName ?>" /><br />
					<span>eMail : </span><br /><input type="text" name="email" value="<?= $_SESSION['user']->email ?>" /><br />
					<span>Ancien Password : </span><input type="password" name="oldPassword" /><br />
					<span>Password : </span><br /><input type="password" name="password" /><br />
					<span>Confirm : </span><br /><input type="password" name="confirm" /><br />
					<span>Date de naissance : </span><br /><input type="text" name="birthday" value="<?= $_SESSION['user']->birthday ?>" /><br />
					<span>Photo du profil : </span><br /><input type="text" name="thumbnail" value="<?= $_SESSION['user']->profilePicture ?>" /><br />
					<a data-role="button" onclick="document.inscriptionForm.submit()">Envoyer</a>
					<a data-role="button" onclick="hideSection('#inscription'); fadeInSection('#news');">Annuler</a>
				</form>
			</div>
			
			<!-- NEWS -->
			<div id="news">
				<?php
				$xml=("http://www-sop.inria.fr/teams/lognet/MYMED/feed.php?rss");
	
				$xmlDoc = new DOMDocument();
				$xmlDoc->load($xml);
	
				//get elements from "<channel>"
				$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
				$channel_title = $channel->getElementsByTagName('title')
				->item(0)->childNodes->item(0)->nodeValue;
				$channel_link = $channel->getElementsByTagName('link')
				->item(0)->childNodes->item(0)->nodeValue;
				$channel_desc = $channel->getElementsByTagName('description')
				->item(0)->childNodes->item(0)->nodeValue;
	
				//output elements from "<channel>"
				echo("<H3 Style='position: relative; width:90%; left: 5%;'><a href='" . $channel_link
				. "'>Actualités - myMed</a>");
				echo "</h3>";
	
				//get and output "<item>" elements
				$x=$xmlDoc->getElementsByTagName('item');
				for ($i=0; $i<=7; $i++) {
					$item_title=$x->item($i)->getElementsByTagName('title')
					->item(0)->childNodes->item(0)->nodeValue;
					$item_link=$x->item($i)->getElementsByTagName('link')
					->item(0)->childNodes->item(0)->nodeValue;
					$item_desc=$x->item($i)->getElementsByTagName('description')
					->item(0)->childNodes->item(0)->nodeValue;
	
					echo ("<div Style='position: relative; width:90%; left: 5%;'><a href='" . $item_link
					. "'>" . $item_title . "</a>");
					echo ("<br />");
					echo ($item_desc . "</div>");
				}
				?>
			</div>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="background-color: white;">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>


