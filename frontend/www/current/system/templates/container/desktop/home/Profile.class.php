<?php
require_once 'system/templates/AbstractTemplate.class.php';
require_once 'system/templates/handler/UpdateProfileHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Profile extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attribute */
	/* --------------------------------------------------------- */
	private /*String*/ $pictureURL = "http://graph.facebook.com//picture?type=large";
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("profile", "profile");
		if($_SESSION['user']->profilePicture != "") {
			$this->pictureURL =  $_SESSION['user']->profilePicture;
		}
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div style="position: absolute; top: 50px; left: 22%;">
			<h1>myMed's home page: <?= $_SESSION['user']->name ?></h1>
		</div>
		<div style="position: absolute; top: 7px; left: 65%; color: white;">
			<img alt="thumbnail" src="<?= $this->pictureURL ?>" width="20px" style="position: absolute;" />
			<div style="position:relative; margin-left: 30px; top:10px;">
				<?= $_SESSION['user']->name ?> : 
				<a href="#login" onclick="document.disconnectForm.submit()" style="font-weight: bold; color: red; text-decoration: none;">Deconnexion</a>
			</div>
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
		$updateProfileHandler = new UpdateProfileHandler();
		$updateProfileHandler->handleRequest();
		?>
		<!-- CONTENT -->
		<div style="position: absolute; margin-left: 30%; left:-202px; top: 160px; width: 200px; overflow: auto;">
			<!-- NOTIFICATION -->
			<?php if($updateProfileHandler->getError()) { ?>
				<div style="color: red;">
					<?= $updateProfileHandler->getError(); ?>
				</div>
			<?php } else if($updateProfileHandler->getSuccess()) { ?>
				<div style="color: #12ff00;">
					<?= $updateProfileHandler->getSuccess(); ?>
				</div>
			<?php } ?>
			
			<!-- Profile -->
			<div style="text-align: left;">
				<img alt="thumbnail" src="<?= $this->pictureURL ?>" width="198px" />
				<br><br>
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				Profile: <a href="<?= $_SESSION['user']->link ?>"><?= $_SESSION['user']->socialNetworkName ?></a><br />
				Reputation: 
				 <?php 
			    	$rand = 3;
			    	$j=0;
			    	while($j<=$rand){ ?>
			    		<img alt="star" src="system/img/star.png" width="20" />
			    		<?php 
			    		$j++;
			    	}
			    	while($j<=4){ ?>
			    		<img alt="star" src="system/img/starGray.png" width="20" />		
			    		<?php 
			    		$j++;
			    	}
			    ?>
			    <br /><br />
			    <a href="#inscription" data-role="button" data-rel="dialog">mise à jour</a>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<?php $this->getHeader(); ?>
		<?php $this->getContent(); ?>
		<?php $this->getFooter(); ?>
	<?php }
}
?>


