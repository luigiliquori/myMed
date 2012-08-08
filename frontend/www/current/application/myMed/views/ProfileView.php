<div id="profile" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content">
	
		<div Style="text-align: left;">
			
			<?php if($_SESSION['user']->profilePicture != "") { ?>
				<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="80">
			<?php } else { ?>
				<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="80">
			<?php } ?>
			
			<br />
			
			<div Style="position: absolute; top:50px; left: 100px; font-weight: bold; font-size: 14pt; text-align: center;">
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
			
			<li  data-role="list-divider" >Profils étendus</li>
			<?php foreach ($this->applicationList as $applicationName) { ?>
				<?php if ($this->applicationStatus[$applicationName] == "on") { ?>
					<li data-icon="refresh">
			    		<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" class="ui-li-icon" />
					    	<a href="<?= MYMED_ROOT ?>application/<?= $applicationName ?>/index.php?action=extendedProfile">
						    	<?= $applicationName ?>
						    	<div Style="position: relative; left: 0px;">
							    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							    		<?php if($i*20-20 < $this->reputation[$applicationName] ) { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
							    		<?php } else { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
							    		<?php } ?>
							    	<? } ?>
						    	</div>
					    	</a>
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
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="profile"  class="ui-btn-active ui-state-persist">Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star">Store</a></li>
			</ul>
		</div>
	</div>

</div>
