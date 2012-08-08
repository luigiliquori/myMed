<?  include("header.php"); 
    $annonce = $this->annonce;
?>

<div data-role="page" >

	<? header_bar(array(
			_("Accueil") => url("main"),
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => null)) ?>
			
	<div data-role="content">

		<? if ($this->hasWriteAccess()) :  ?>
			<a data-ajax="false" data-role="button" data-theme="g" data-icon="edit" data-inline="true"
				href="<?= url("annonce:edit", array("id" => $annonce->id)) ?>">
				<?= _("Éditer") ?>
			</a>
		
			<a data-ajax="false" data-role="button" data-theme="r"data-icon="delete" data-inline="true"
				href="<?= url("annonce:delete", array("id" => $annonce->id)) ?>">
				<?= _("Supprimer") ?>
			</a>
		<? endif ?>
		
		<? if (($this->extendedProfile == null) || // Guest
				($this->extendedProfile instanceof ProfileBenevole)): // Benevole ?>
			<? if (is_true($annonce->promue)) : ?>
			<? elseif (is_true($annonce->isPassed())): ?>
				<span class="mm-tag mm-warn"><?= _("Annonce passée") ?></span>	
			<? else: ?>
				<a data-ajax="false" data-role="button" data-theme="e" data-icon="check" data-inline="true"
					href="<?= url("candidature:create", array("annonceID" => $annonce->id)) ?>">
						<?= _("Postuler") ?>
				</a>
			<? endif ?>
		<? endif ?>
		
		<? if (is_true($annonce->promue)) : ?>
			<span class="mm-tag mm-warn"><?= _("Annonce déjà promue") ?></span>	
		<? else: ?>
			<? if ($this->hasWriteAccess()) : ?>
				<a data-ajax="false" data-role="button" data-theme="e" data-icon="check" data-inline="true"
					href="<?= url("annonce:promote", array("id" => $annonce->id)) ?>">
					<?= _("Marquer comme promue") ?>
				</a>
			<? endif ?>
		<? endif ?>
		
		<form target="#" >
			<? global $READ_ONLY; $READ_ONLY=true; ?>
			<? require("AnnonceForm.php"); ?>
		</form>
		
		<? if ($this->hasWriteAccess()) :  	 		
			$candidatures = $annonce->getCandidatures(); ?>
			 	<div data-role="header" data-theme="b">
			 		<h3><?= _("Candidatures") ?></h3>
			 	</div>
			 <? if (sizeof($candidatures) == 0) : ?>
			 	<p><?= _("Aucune candidature") ?></p>
			 <? elseif ($this->extendedProfile instanceof ProfileNiceBenevolat) : ?>  	 
			 	<ul data-role="listview" data-theme="d" data-inset="true">
			 		<? foreach($candidatures as $candidature) : ?>
			 			<li><a href="<?= url("candidature:view", array("id" => $candidature->id)) ?>">
			 				<b><?= $candidature->publisherName ?></b>
			 				<p class="ui-li-aside"><strong><?= $candidature->begin ?></strong></p>
			 			</a></li>
			 		<? endforeach ?>
			 	</ul>
			 <? else: ?>
			 	<p>
			 		<?= sprintf(_("Il y a actuellement %d candidatures pour cette annonce."), sizeof($candidatures)) ?><br/>
			 		<?= _("Contactez Nice Bénévolat pour en savoir davantage.") ?>
			 	</p>
			 <? endif;
		endif ?>
	</div>
	
</div>
	
<?php include("footer.php"); ?>
