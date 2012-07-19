<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	<h3>Liste des annonces</h3>
	
	<div data-role="content">
		<ul data-role="listview" data-theme="g">
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