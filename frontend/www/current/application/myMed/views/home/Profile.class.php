<?php

require_once MYMED_ROOT . 'system/controllers/MenuController.class.php';
require_once MYMED_ROOT . 'system/controllers/UpdateProfileController.class.php';

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
		$menuController = new MenuController();
		$menuController->handleRequest();
		$updateController = new UpdateProfileController();
		$updateController->handleRequest();
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" style="position: relative; left: 5%; width: 90%;">
		
			<!-- Disconnect -->
			<form action="#" method="post" name="disconnectForm" id="disconnectForm">
			<input type="hidden" name="disconnect" value="1" /></form>
		
			<!-- Profile -->
			<div>
				<h3>Profile</h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
				<?php } ?>
				<br />
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				<div data-role="controlgroup" data-type="horizontal">
					<a href="#inscription" data-role="button" data-rel="dialog" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
					<a href="#login" onclick="document.disconnectForm.submit()" data-inline="true" rel="external" data-role="button" data-theme="r">Deconnexion</a>
				</div>
			</div>
	
			<!-- FRIENDS -->
			<div data-theme="d">
				<?php $i=0; ?>
				<?php foreach ($_SESSION['friends'] as $friend ) { ?>
					<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
					<?php $i++; ?>
				<?php } 
				if($i == 0) { 
// 					$socialNetworkConnection =  new SocialNetworkConnection();
// 					foreach($socialNetworkConnection->getWrappers() as $wrapper) {
// 						$url = TARGET == "mobile" ? str_replace("www", "m", $wrapper->getLoginUrl()) . "&display=touch" :  $wrapper->getLoginUrl();
// 						echo "<a href='" . $url . "' onClick='showLoadingBar(\"redirecton en cours...\")'>" . $wrapper->getSocialNetworkButton() . "</a>";
// 					}
				?>
				<!-- CONNECTION FACEBOOK -->
		 	    <div id="fb-root"></div>
			    <script>
			        window.fbAsyncInit = function() {
			          FB.init({
			            appId      : '<?= Facebook_APP_ID ?>',
			            status     : true, 
			            cookie     : true,
			            xfbml      : true,
			            oauth      : true,
			          });
			          FB.Event.subscribe('auth.login', function(response) {
			              window.location.reload();
			            });
			        };
			        (function(d){
			           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
			           js = d.createElement('script'); js.id = id; js.async = true;
			           js.src = "//connect.facebook.net/en_US/all.js";
			           d.getElementsByTagName('head')[0].appendChild(js);
			         }(document));
			    </script>
			    <div class="fb-login-button" scope="email,read_stream">Facebook</div>
			    <!-- END CONNECTION FACEBOOK -->
				<?php } else { ?>
					<!-- LIKE BUTTON -->
					<br /><br />
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="http://www.mymed.fr" data-send="true" data-width="450" data-show-faces="true"></div>
					<br /><br />
				<?php } ?>
			</div>
		</div>
	<?php }
	
}
?>


