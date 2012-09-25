<? include("header.php"); ?>
			<!-- FRIENDS -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
				data-content-theme="c">
				<h3>Réseau social</h3>
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
				
				<!-- SHARE THIS -->
				<div id="hidden-sharethis">
					<span class='st_facebook_large' displayText='Facebook'></span>
					<span class='st_twitter_large' displayText='Tweet'></span>
					<span class='st_linkedin_large' displayText='LinkedIn'></span>
					<span class='st_email_large' displayText='Email'></span>
				</div>
				
			</div>