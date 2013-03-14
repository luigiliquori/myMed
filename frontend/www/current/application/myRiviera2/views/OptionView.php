<? include("header.php"); ?>

<div id="option" data-role="page">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="?action=main" data-icon="arrow-l" data-ajax="false"/><?= _("Main menu")?></a>
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
		<a href="?action=main#search" data-transition="none" data-icon="search">Rechercher</a>
	</div>

	<!-- CONTENT -->
	<div data-role="content"
		style="font-size: 10pt; margin: 50px auto 0 auto; width: 90%;">
		<!-- UPDATE POIs -->
		<div data-role="collapsible-set" data-inset="true">

			<!-- Profile -->
			<div data-role="collapsible" data-collapsed="false" data-theme="d"
				data-content-theme="c">
				<h3>Profil</h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
				<?php } ?>
				<br />
				<!-- <a onclick="capturePhoto();" type="button" data-theme="d">Prendre une photo</a>  -->
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				<div data-role="controlgroup" data-type="horizontal">
					 <a href="#inscription" data-role="button" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a> 
					<!-- <a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a> -->
				</div>
			</div>

			<?php //include 'facebook.php'; ?>

			<?php /*
			<!-- COMMENT -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
				data-content-theme="c">
				
				<h3>Commentaires</h3>
				<p>Un commentaire, une idée, une demande? Laissez nous un commentaire!</p>
				
				<form action="" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm"
					enctype="multipart/form-data" data-ajax="false">
					
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>Admin" /> 
					<input type="hidden" name="method" value="publish" /> 
					<input type="hidden" name="numberOfOntology" value="3" />

					<!-- KEYWORD -->
					<input type="hidden" name="keyword" value="myRivieraTest" />
					<?php $keywordBean = new MDataBean("keyword", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keywordBean)); ?>">
				
					<!-- EMAIL -->
					<input type="hidden" name="email" value="<?= $_SESSION['user']->email ?>"/>
					<?php $keywordBean = new MDataBean("email", null, TEXT); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($keywordBean)); ?>">
				
					<!-- TEXT -->
					<textarea name="text" rows="" cols=""></textarea>
					<?php $text = new MDataBean("text", null, TEXT); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($text)); ?>">
					<br />
					
					<input type="submit" value="Publier" />
				</form>
			</div>
			
			<!-- HELP -->
			<div data-role="collapsible" data-collapsed="true" data-theme="d"
				data-content-theme="c" style="text-align: left;">
				<h3>Aide</h3>
				<h2>Rechercher</h2>
				<p>
					La recherche d'itinéraire utilise <a href="http://www.ceparou06.fr/">Ceparou06</a>
					, en cas d'échec vous serez redirigé vers un itinéraire Google Maps.
					<br>
					<b>Rayon de recherche</b>:
					Les points d'intérêts sont affichés à l'intérieur de cette zone.
					<br>
					<b>Points d'intérêts</b>:
					Ils désignent les types d'établissements, d'évênements que vous
					souhaitez afficher sur la carte.
					<br>
					<b>Types de Trajet Cityway</b>
					Ces champs permettent de paramétrer votre recherche d'itinéraire.
				</p>
				<h2>Profil</h2>
				<p>Ce champ donne accès à votre profil myMed.</p>
				<h2>Réseau social</h2>
				<p>En vous connectant avec Facebook, vous chargerez les positions de
					vos amis (acceptant la géolocalisation), disponibles dans la
					recherche d'itinéraire par le bouton + du champs Arrivée.</p>
			</div>
			
			*/ ?>

		</div>
	</div>
	

</div>

<? include("footer.php"); ?>