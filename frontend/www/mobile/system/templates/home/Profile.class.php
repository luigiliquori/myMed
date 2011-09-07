<?php

require_once 'system/templates/home/AbstractHome.class.php';
require_once dirname(__FILE__).'/handler/UpdateProfileHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Profile extends AbstractHome {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("profile");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header">
			<h1>myMed profile</h1>
		</div>
	<?php }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {
		$updateProfileHandler = new UpdateProfileHandler();
		$updateProfileHandler->handleRequest();
		?>
		<!-- CONTENT -->
		<div class="content">
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
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="180" height="150">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180" height="150">
				<?php } ?>
				 <?php if($_SESSION['user']->email == 'lvanni@inria.fr') { ?>
				   <br>
			 	   <a href="?admin" rel="external" data-role="button" data-inline="true" data-theme="a">Admin</a>
			    <?php } ?>
				<br><br>
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				Reputation: 
				 <?php 
			    	$rand = rand(0, 4);
			    	$j=0;
			    	while($j<=$rand){ ?>
			    		<img alt="star" src="img/star.png" width="20" />
			    		<?php 
			    		$j++;
			    	}
			    	while($j<=4){ ?>
			    		<img alt="star" src="img/starGray.png" width="20" />		
			    		<?php 
			    		$j++;
			    	}
			    ?>
			    <br /><br />
			    <a href="#inscription" data-role="button" data-rel="dialog">mise à jour</a>
			    <a href="#login" onclick="document.disconnectForm.submit()" data-role="button" data-theme="r">Deconnexion</a>
			</div>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { 
		parent::printTemplate();
		include('updateProfile.php');
	}
}
?>


