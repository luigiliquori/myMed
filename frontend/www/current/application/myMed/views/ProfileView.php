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
				<a href="#updateProfile" data-role="button" data-inline="true" data-theme="b" data-icon="refresh" data-mini="true">mise à jour</a>
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
					    	<a href="<?= APP_ROOT ?>/../<?= $applicationName ?>/index.php?action=extendedProfile">
						    	<?= $applicationName ?>
						    	<div Style="position: relative; left: 0px;">
							    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							    		<?php if($i*20-20 < $this->reputation[$applicationName . EXTENDED_PROFILE_PREFIX] ) { ?>
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
	    </ul>
		
		<br /><br />
		
		<?php include('socialNetwork.php') ?>
		
		<br /><br />
		
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user"  class="ui-btn-active ui-state-persist">Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star">Store</a></li>
			</ul>
		</div>
	</div>

</div>
