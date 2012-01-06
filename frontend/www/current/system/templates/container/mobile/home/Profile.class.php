<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Profile extends Home {
	
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
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<!-- Profile -->
			<div class="ui-grid-a" style="text-align: left;">
				<div class="ui-block-a">
					<?php if($_SESSION['user']->profilePicture != "") { ?>
						<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
					<?php } else { ?>
						<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
					<?php } ?>
				</div>
				<div class="ui-block-b"  style="text-align: left;">
				 <a href="#inscription" data-role="button" data-rel="dialog" data-inline="true">mise à jour</a><br />
			    </div>
			</div>
			<br />
		    <div style="text-align: left;">
			    Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
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
		  	</div>
		  	<br />
		  	<a href="mobile_binary<?= MOBILE_PARAMETER_SEPARATOR ?>logout" data-role="button" data-theme="r">Deconnexion</a>
		</div>
	<?php }
	
}
?>


