<? include("header.php"); ?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<p>Bonjour <strong>Nice Bénévolat</strong>, que voulez vous faire ?</p>
		
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false" 
		   href="<?= url("listAnnonces") ?>">
		 	Gérer les annonces / candidatures
		</a>
		 	
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false"
			href="<?= url("listAssociations") ?>">
			Gérer les associations
		</a>
		
		<a data-role="button" class="mm-left" data-icon="arrow-r" 
		   data-theme="e" data-ajax="false"
			href="<?= url("listBenevoles") ?>">
			Gérer les bénévoles
		</a>
	</div>
</div>
	
<?php include("footer.php"); ?>