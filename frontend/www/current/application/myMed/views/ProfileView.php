<div id="profile" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content" class="content">
	
		<div Style="text-align: left;">
			
			<?php if($_SESSION['user']->profilePicture != "") { ?>
				<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="80">
			<?php } else { ?>
				<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="80">
			<?php } ?>
			
			<br />
			
			<div Style="position: absolute; top:50px; left: 120px; font-weight: bold; font-size: 14pt; text-align: center;">
				<?= $_SESSION['user']->firstName ?> <?= $_SESSION['user']->lastName ?> <br /><br />
				<div data-role="controlgroup" data-mini="true"  data-type="horizontal">
					<a href="#updateProfile" data-role="button" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
					<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-theme="r" >Deconnexion</a>
				</div>
			</div>
			
		</div>
		
		<br /><br />
		
		<ul data-role="listview"  data-mini="true">
			
			<li  data-role="list-divider" >Informations générales</li>
			<li data-icon="refresh">
				<img alt="eMail: " src="<?= APP_ROOT ?>/img/email_icon.png" class="ui-li-icon" />
				<a href="#updateProfile">Email<p><?= $_SESSION['user']->email ?></p></a>
			</li>
			<li data-icon="refresh">
				<img alt="Date de naissance: " src="<?= APP_ROOT ?>/img/birthday_icon.png" class="ui-li-icon"/>
				<a href="#updateProfile">Date de naissance<p><?= $_SESSION['user']->birthday ?></p></a>
			</li>
			
			<li  data-role="list-divider" >Mes applications</li>
			<?php foreach ($this->applicationList as $applicationName) { ?>
				<?php if ($this->applicationStatus[$applicationName] == "on") { ?>
					<li data-icon="delete">
			    		<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" class="ui-li-icon" />
				    	
				    	<a href="#" onclick="printDialog('hidden-<? $applicationName ?>DialogBox', '<?= $applicationName ?>');">
					    	<?= $applicationName ?>
					    	<div Style="position: relative; width: 100px; height: 20px;">
					    		<div Style="position: absolute; width: <?= $this->reputation[$applicationName] ?>px; height: 20px; top:0px; left:0px;  background-color: #ffe400;"></div>
					    		<img alt="rep" src="<?= APP_ROOT ?>/img/rep.png" />
					    	</div>
				    	</a>
				    	
			    		<div style="display: none;">
							<div id="hidden-<? $applicationName ?>DialogBox">
								<a href="#" data-role="button" data-theme="r" data-icon="delete">Effacer le profil étendu</a>
								<a href="#" onclick="SetCookie('<?= $applicationName ?>Status', 'off', 365); window.location.reload()" data-role="button" data-theme="b" data-icon="minus">Désactiver l'application</a>
								<hr />
								<a href="?action=main" data-role="button" data-theme="c" data-ajax="false">Annuler</a>
							</div>
						</div> 
			    	</li>
			    <?php } 
		    } ?>
			
			<li data-role="list-divider">Réseau social</li>
			<li><p>
				<?php $i=0; ?>
				<?php foreach ($_SESSION['friends'] as $friend ) { ?>
					<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
					<?php $i++; ?>
				<?php } 
				if($i == 0) { ?>
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
				<?php } ?>
				</p>
			</li>
			
		</ul>
		
		<br /><br />
		
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="profile"  class="ui-btn-active ui-state-persist">Profil</a></li>
				<li><a href="#" data-rel="dialog" data-icon="star" onClick="printDialog('hidden-sharethis', 'Partagez');">Partagez</a></li>
			</ul>
		</div>
	</div>

</div>
