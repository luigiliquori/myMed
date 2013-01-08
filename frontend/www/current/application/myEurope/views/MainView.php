<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="home">

	<? print_header_bar(false, "defaultHelpPopup"); ?>
	
	<div data-role="content" >
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- ------------------ -->
		<!-- CONTENT -->
		<!-- ------------------ -->
		<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
			<h3><?= _("welcome") ?></h3>
			<p><?= _("myEurope est une application du projet Alcotra myMed, qui vise à mettre en relations des maires et des communes transfrontalières.") ?><br />
			<?= _("L'idée est de frounir un outils pour simplifier et aider la création de projet Européen comme myMed") ?></p>
		</div>
		
		<br />
		
		<?php if ($_SESSION['user']->is_guest) { ?>
		<a href="index.php?action=extendedProfile" rel="external" data-icon="signin" data-role="button" ><?=_("Sign in")?></a><br />
		<?php } ?>
		<a href="#search" data-role="button" data-icon="search"><?= _("Search a partnership offer") ?></a><br />
		<a href="index.php#post" data-icon="pencil" data-role="button" rel="external" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("Insert a partnership offer") ?></a><br />
		<a href="?action=extendedProfile" data-icon="user" rel="external" data-role="button" <?= $_SESSION['user']->is_guest ? " class='ui-disabled'" : "" ?>><?= _("Profile") ?></a><br />
		<a href="#Blog" data-icon="comment" data-role="button"><?= _("Blog") ?></a><br />
		<a href="?action=about" data-icon="info-sign" data-role="button" data-inline="true" style="position: absolute; right: 10px;"><?=_("Credits")?></a>
			
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<h3><?= _("Comment ça marche") ?> ?</h3>
			<ul data-role="listview" data-theme="e">	
				<li>
					<img alt="search" src="img/icons/search.png" Style="width: 64px">
					<p><strong><?= _("Search a partnership offer") ?></strong></p>
					<p><?= _("Recherchez ici directement des offres de partenariat") ?></p>
				</li>
				<li>
					<img alt="publish" src="img/icons/publish.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Insert a partnership offer") ?></strong></p>
						<p><?= _("Insérez vos propres offres de partenariat") ?></p>
				</li>
				<li>
					<img alt="blog" src="img/icons/blog.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Blog") ?></strong></p>
						<p><?= _("Laissez votre avis, partagez votre experience et enrichissez le réseau myEurope") ?></p>
				</li>
				<li>
					<img alt="profile" src="img/icons/profile<?= $_SESSION['user']->is_guest ? "_guest" : "" ?>.png" Style="position:absolute; left:0px; width: 64px">
					<p><strong><?= _("Profile") ?></strong></p>
					<p><?= _("Complétez et gérez votre profil pour une meilleurs visibilité dans myEurope") ?></p>
				</li>
			</ul>
			<br />	
			<center><a href="#" data-role="button" data-icon="ok" data-inline="true" data-theme="e" data-rel="back" data-direction="reverse">Ok</a></center>
		</div>
		
	</div>
</div>

<? include("SearchView.php"); ?>
<? include("PublishView.php"); ?>
<? include("BlogView.php"); ?>

<?php include("footer.php")?>


