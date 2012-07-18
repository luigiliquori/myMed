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
		
		<ul data-role="listview">
			<li  data-role="list-divider" >Informations générales</li>
			<li><img alt="eMail: " src="<?= APP_ROOT ?>/img/email_icon.png" class="ui-li-icon" /><a href="#updateProfile"><?= $_SESSION['user']->email ?></a></li>
			<li><img alt="Date de naissance: " src="<?= APP_ROOT ?>/img/birthday_icon.png" class="ui-li-icon"/><a href="#updateProfile"><?= $_SESSION['user']->birthday ?></a></li>
			<li  data-role="list-divider" >Profil étendu</li>
			<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
				$testApplication = array("myBen", "myFSA", "myMemory");
			    while (false !== ($file = readdir($handle))) {
			    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && in_array($file, $testApplication)) { ?>
				    	<li>
				    		<img alt="<?= $file ?>" src="../../application/<?= $file ?>/img/icon.png" class="ui-li-icon" />
					    	<a href="/application/<?= $file ?>?action=extendedProfile" rel="external" class="myIcon"><?= $file ?></a>
				    	</li>
				    <?php } 
			    } 
			} ?>
		</ul>
		
		<br /><br />
		
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="b">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="profile"  class="ui-btn-active ui-state-persist">Profil</a></li>
			</ul>
		</div>
	</div>

</div>
