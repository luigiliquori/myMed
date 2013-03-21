<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<? include("header.php"); ?>

<div id="option" data-role="page">

	<!-- Header -->
	<div data-role="header" data-theme="b" data-position="fixed">
		<? if(strpos($_SERVER['HTTP_REFERER'],"?action=profile")) : ?>
			<a data-rel="back" data-icon="arrow-l" data-ajax="false"/><?= _("Back")?></a>
		<? else: ?>
			<a href="?action=main" data-icon="arrow-l" data-ajax="false"/><?= _("Back")?></a>
		<? endif ?>
	
		<h1><?php echo APPLICATION_NAME." v1.0 alpha"?></h1>
	</div>

	<!-- CONTENT -->
	<div data-role="content">
		<br><br>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">

			<!-- User details -->
			<li data-role="list-divider"><?= _("User details") ?></li>	
			
			<li>
				<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">
					<div class="ui-block-a" style="width: 120px;">
	 	 	 	 	<? if($_SESSION['user']->profilePicture != "") { ?>
							<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
		 		 	<? } else { ?>
							<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
		 		 	<? } ?>
		 		 	</div>
		 		 	<div class="ui-block-b" style="margin-top: 7px;margin-bottom:7px">
						<!-- <a onclick="capturePhoto();" type="button" data-theme="d">Prendre une photo</a>  -->
						<p><strong><?= $_SESSION['user']->name ?></strong><br></p>
						<p><strong><?= _("Date of birth")?>: </strong><?= $_SESSION['user']->birthday ?></p>
						<p><strong><?= _("E-mail")?>: </strong><?= $_SESSION['user']->email ?></p>
						<!--<div data-role="controlgroup" data-type="horizontal">
							  <a href="#inscription" data-role="button" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
							  <a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
						</div>-->
					</div>
				</div>
			</li>
		</ul>
		<center>
			<a type="button" href="?action=main"  data-theme="d" data-icon="home" data-inline="true"><?= _('Home') ?></a>
		
			<!-- Upgrade profile from facebook/google+ to myMed account. Impossible from twitter (no email) -->
		 <? if(isset($_SESSION['userFromExternalAuth']) && (!isset($_SESSION['user']->login)) && $_SESSION['userFromExternalAuth']->socialNetworkName!="Twitter-OAuth"): ?>
				<a type="button" href="?action=UpgradeAccount&method=migrate"  data-theme="g" data-icon="pencil" data-inline="true"><?= _('Create a myMed profile') ?></a>
		 <? endif; ?>
		</center>
	</div>
</div>

<? include("footer.php"); ?>

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

