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
		<div style="position: absolute; top: 50px; left: 20%;">
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
		$updateProfileHandler = new UpdateProfileHandler();
		$updateProfileHandler->handleRequest();
		?>
		<!-- CONTENT -->
		<div style="position: absolute; margin-left: 25%; left:-200px; top: 210px; width: 200px; overflow: auto;">
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
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="150px">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="150px">
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
				Profile: <a href="<?= $_SESSION['user']->link ?>"><?= $_SESSION['user']->socialNetwork ?></a><br />
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
				
				<!-- FRIENDS STREAM -->
				<div style="background-color: #415b68; color: white; width: 200px; font-size: 15px; font-weight: bold;">my Friends</div>
				<div style="position:relative; height: 150px; width: 200px; overflow: auto; background-color: #edf2f4; top:0px;">
					<?php while (list(, $value) = each($_SESSION['friends'])) { ?>
						<a href=http://www.facebook.com/#!/profile.php?id=<?= $value->id ?>"><?= $value->name ?></a><br />
					<?php } ?>
				</div>
				
			</div>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		$this->getHeader();
		$this->getContent();
		$this->getFooter();
	}
}
?>


