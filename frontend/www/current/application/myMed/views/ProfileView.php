<div id="profile" data-role="page">

	<? include_once("header-bar-light.php"); ?>
	<? tabs_main("profile", array(
			"home" => array(_('Applications'), "grid"),
			"profile" => array(_('Profile'), "user"),
			"store" => array(_('Store'), "star"),
	), true) ?>
	<? include("notifications.php")?>
	<div data-role="content">
	
		<?= debug_r($_SESSION['user'])?>
	
		<div Style="text-align: left;">
			<br />
			<?php if($_SESSION['user']->profilePicture != "") { ?>
				<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="80">
			<?php } else { ?>
				<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="80">
			<?php } ?>
			
			<div Style="display:inline-block; font-weight: bold; font-size: 14pt; text-align: center;">
				<?= $_SESSION['user']->firstName ?> <?= $_SESSION['user']->lastName ?> <br />
				<a href="#updateProfile" data-role="button" data-inline="true" data-theme="b" data-icon="refresh" data-mini="true">mise à jour</a>
				<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-mini="true" data-theme="r" data-icon="power" class="ui-btn-right">Déconnexion</a>
				
			</div>
			
		</div>
		
		<br /><br />
		
		<ul data-role="listview" data-mini="true">
			
			<li  data-role="list-divider">Informations générales</li>
			<li data-icon="refresh">
				<img alt="eMail: " src="<?= APP_ROOT ?>/img/email_icon.png" width="50" Style="margin-left: 5px; top:5px;"/>
				<a href="#updateProfile">Email<p><?= $_SESSION['user']->email ?></p></a>
			</li>
			<li data-icon="refresh">
				<img alt="Date de naissance: " src="<?= APP_ROOT ?>/img/birthday_icon.png" width="50" Style="margin-left: 5px; top:5px;"/>
				<a href="#updateProfile">Date de naissance<p><?= $_SESSION['user']->birthday ?></p></a>
			</li>
			<li data-icon="refresh">
				<img alt="Langue: " src="<?= APP_ROOT ?>/img/<?= $_SESSION['user']->lang ?>_flag.png" width="50" Style="margin-left: 5px; top:5px;"/>
				<a href="#Lang">Langue<p><?= $_SESSION['user']->lang ?></p></a>
			</li>
			
			<li  data-role="list-divider">Profils</li>
			<?php foreach ($this->applicationList as $applicationName) { ?>
				<?php if ($this->applicationStatus[$applicationName] == "on") { ?>
					<li data-icon="refresh" >
			    		<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" width="50" Style="margin-left: 5px; top:5px;"/>
					    	<a href="<?= APP_ROOT ?>/../<?= $applicationName ?>/index.php?action=extendedProfile" rel="external">
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
		
		<br /><br />
		
	</div>


</div>

<? if(!empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>
