<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	
	
	<div data-role="content">
	
		<div data-role="header" data-theme="e" >
			<h3>Liste des annonces</h3>
		</div>
		
		<ul data-role="listview" data-theme="d" data-inset="true">
			<?  foreach ($this->annonces as $annonce) : ?>
				<li>
					<a 	data-ajax="false"
						href="<?= url("annonce:details", array("id" => $annonce->id)) ?>">
						<?= $annonce->titre ?>
					</a>
				</li>
			<? endforeach ?>
		</ul>
		
	</div>
	
</div>
	
<?php include("footer.php"); ?>