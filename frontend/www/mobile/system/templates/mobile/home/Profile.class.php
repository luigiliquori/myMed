<?php

require_once dirname(__FILE__).'/AbstractHome.class.php';
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
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="180">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180">
				<?php } ?>
				 <?php if($_SESSION['user']->email == 'laurent.vanni@inria.fr') { // DEBUG MODE (JUST FOR TESTING) ?> 
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
			    <?php
			    $detect = new Mobile_Detect();
			    if($detect->isDevice("isIphone") || $detect->isDevice("isIpad")) { ?>
			  		<a href="mobile_binary<?= MOBILE_PARAMETER_SEPARATOR ?>logout" data-role="button" data-theme="r">Deconnexion</a>
				<?php } else { ?>
					<a href="#login" onclick="document.disconnectForm.submit()" data-role="button" data-theme="r">Deconnexion</a>
				<?php } ?>
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


