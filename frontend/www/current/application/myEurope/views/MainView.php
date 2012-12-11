<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="home">

	<? print_header_bar(false, "defaultHelpPopup"); ?>
	
	<div data-role="content" >
	
		<? print_notification($this->success.$this->error); ?>
	
		<!-- ------------------ -->
		<!-- CONTENT -->
		<!-- ------------------ -->
		<div class="ui-grid-a" Style="text-align: center;">
			<div class="ui-block-a">
				<a href="#search" class="myEuropeIcon">
					<img alt="search" src="img/icons/search.png" Style="width: <?= $this->detect->isMobile() ? "80" : "20" ?>%">
				</a>
				<br />
				<span class="myEuropeIcon"><?= _("Search a partnership offer") ?></span>
			</div>
			
			<div class="ui-block-b">
				<a href="#post">
					<img alt="search" src="img/icons/publish.png" Style="width: <?= $this->detect->isMobile() ? "80" : "20" ?>%">
				</a>
				<br />
				<span class="myEuropeIcon"><?= _("Insert a partnership offer") ?></span>
			</div>
		</div>
		<br /><br />
		<div class="ui-grid-a" Style="text-align: center;">
			<div class="ui-block-a">
				<a href="#Blog">
					<img data-ajax="false" alt="search" src="img/icons/blog.png" Style="width: <?= $this->detect->isMobile() ? "80" : "20" ?>%">
				</a>
				<br />
				<span class="myEuropeIcon"><?= _("Blog") ?></span>
			</div>
			
			<div class="ui-block-b">
				<a href="#infos">
					<img alt="search" src="img/icons/info.png" Style="width: <?= $this->detect->isMobile() ? "80" : "20" ?>%">
				</a>
				<br />
				<span class="myEuropeIcon"><?= _("Informations") ?></span>
			</div>
		</div>
		<br /><br />
		<div class="ui-grid-a" Style="text-align: center;">
			<div class="ui-block-a">
				<a href="?action=extendedProfile" rel="external">
					<img alt="search" src="img/icons/profile<?= $_SESSION['user']->is_guest ? "_guest" : "" ?>.png" Style="width: <?= $this->detect->isMobile() ? "80" : "20" ?>%">
				</a>
				<br />
				<span class="myEuropeIcon"><?= _("Profile") ?></span>
			</div>
			
		</div>
		
		<!-- ------------------ -->
		<!-- HELP POPUP -->
		<!-- ------------------ -->
		<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
			<h2><?= _("Bienvenu dans myEurope") ?></h2>
			<p><?= _("myEurope est une application du projet Alcotra myMed, qui vise à mettre en relations des maires et des communes transfrontalières.") ?><br />
			<?= _("L'idée est de frounir un outils pour simplifier et aider la création de projet Européen comme myMed") ?></p>
			<br />
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
					<img alt="info" src="img/icons/info.png" Style="position:absolute; left:0px; width: 64px">
						<p><strong><?= _("Informations") ?></strong></p>
						<p><?= _("Venez récupérer des informations et des liens utiles pour la création de Projet Européen") ?></p>
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

<?php 
function tabs_info($item){
	tabs_simple($item, 'Infos');
}
?>
<? if($_SESSION['user']->lang=="it"): ?>
	<? include("infos_it.php"); ?>
<? else: ?>
	<? include("infos.php"); ?>
<? endif; ?>

