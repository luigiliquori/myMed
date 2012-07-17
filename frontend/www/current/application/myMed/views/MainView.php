<? include("header.php"); ?>

<div id="home" data-role="page">

	<div data-role="header" data-theme="b">
			
		<a href="#" data-rel="dialog" data-icon="star" onClick="printShareDialog();">Partagez</a>
		<div style="display: none;">
			<div id="hidden-sharethis">
				<span class='st_facebook_large' displayText='Facebook'></span>
				<span class='st_twitter_large' displayText='Tweet'></span>
				<span class='st_linkedin_large' displayText='LinkedIn'></span>
				<span class='st_email_large' displayText='Email'></span>
			</div>
		</div> 
		<h1><?= APPLICATION_NAME ?></h1>
		<a href="#About" data-icon="gear">Option</a>
		<? include("notifications.php")?>
		
	</div>
	
	<div data-role="content" class="content">
	
		<div class="ui-grid-b" Style="padding: 10px;">
			<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
				$hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp");
				$column = "a";
			    while (false !== ($file = readdir($handle))) {
			    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $hiddenApplication)) { ?>
				    	<div class="ui-block-<?= $column ?>">
					    	<a
					    	href="/application/<?= $file ?>"
					    	rel="external"
					    	class="myIcon"><img alt="<?= $file ?>" src="../../application/<?= $file ?>/img/icon.png" width="50px" ></a>
					    	<br>
					    	<span style="font-size: 9pt; font-weight: bold;"><?= $file ?></span>
				    	</div>
				    	<?php 
				    	if($column == "a") {
				    		$column = "b";
				    	} else if($column == "b") {
				    		$column = "c";
				    	} else if($column == "c") {
				    		$column = "a";
				    		echo '</div><br /><div class="ui-grid-b">';
				    	}
			    	} 
			    } 
			} ?>
		</div>
			
		<!-- LOGO -->
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#home" data-transition="none" data-back="true" data-icon="grid" class="ui-btn-active ui-state-persist">Applications</a></li>
					<li><a href="#profile" data-transition="none" data-icon="profile">Profil</a></li>
				</ul>
			</div>
		</div>
			
	</div>

</div>

<div id="profile" data-role="page">

	<div data-role="header" data-theme="b">
			
		<a href="#" data-rel="dialog" data-icon="star" onClick="printShareDialog();">Partagez</a>
		<div style="display: none;">
			<div id="hidden-sharethis">
				<span class='st_facebook_large' displayText='Facebook'></span>
				<span class='st_twitter_large' displayText='Tweet'></span>
				<span class='st_linkedin_large' displayText='LinkedIn'></span>
				<span class='st_email_large' displayText='Email'></span>
			</div>
		</div> 
		<h1><?= APPLICATION_NAME ?></h1>
		<a href="#About" data-icon="gear">Option</a>
		<? include("notifications.php")?>
		
	</div>
	
	<div data-role="content" class="content">
	
		<div>
				<h3>Profil</h3>
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
					<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r">Deconnexion</a>
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
			
		<!-- LOGO -->
		<?php include(MYMED_ROOT . '/system/views/logos.php'); ?>
		
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
					<li><a href="#profile" data-transition="none" data-icon="profile"  class="ui-btn-active ui-state-persist">Profil</a></li>
				</ul>
			</div>
		</div>
			
	</div>

</div>

<? include("footer.php"); ?>