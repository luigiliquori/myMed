
<div id="profile" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content" class="content">
		
		<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d">
			<h3>Informations générales</h3>
			<div Style="text-align: left;">
				<h3>Profil</h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="80">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="80">
				<?php } ?>
				
				<br />
				
				<div Style="position: absolute; top:200px; left: 150px; font-weight: bold; font-size: 14pt; text-align: center;">
					<?= $_SESSION['user']->firstName ?> <?= $_SESSION['user']->lastName ?> <br /><br />
					<a href="#updateProfile" data-role="button" data-inline="true" data-theme="b" data-icon="refresh" data-mini="true">mise à jour</a>
				</div>
			</div>
			<br /><br />
			<ul data-role="listview"  data-mini="true" data-filter="true">
				<li data-role="list-divider">liste des membres</li>
				<?php include_once 'mailList.php';?>
			</ul>
		</div>
	
	</div>

	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user" class="ui-btn-active ui-state-persist">Profil</a></li>
			</ul>
		</div>
	</div>

</div>

<? if(!empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>