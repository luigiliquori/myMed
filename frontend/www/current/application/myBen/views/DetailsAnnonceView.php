<?  include("header.php"); 
    $annonce = $this->annonce;
?>

<div data-role="page" >

	<? header_bar(array(
			"Accueil" => url("main"),
			"Annonces" => url("listAnnonces"),
			$annonce->titre => null)) ?>
			
	<div data-role="content">

		<? if ($this->hasWriteAccess()) :  ?>
			<a data-ajax="false" data-role="button" data-theme="g" data-icon="edit" data-inline="true"
				href="<?= url("annonce:edit", array("id" => $annonce->id)) ?>">
				Éditer
			</a>
		
			<a data-ajax="false" data-role="button" data-theme="r"data-icon="delete" data-inline="true"
				href="<?= url("annonce:delete", array("id" => $annonce->id)) ?>">
				Supprimer
			</a>
		<? endif ?>
		
		<? if ((($this->extendedProfile == null) || 
				($this->extendedProfile instanceof ProfileBenevole)) 
				&& 
				(!is_true($annonce->promue))) :?>
			<a data-ajax="false" data-role="button" data-theme="e" data-icon="check" data-inline="true"
				href="<?= url("candidature:create", array("annonceID" => $annonce->id)) ?>">
				Postuler
			</a>
		<? endif ?>
		
		<? if (is_true($annonce->promue)) : ?>
			<div>Annonce déjà promue</div>	
		<? else: ?>
			<? if ($this->hasWriteAccess()) : ?>
				<a data-ajax="false" data-role="button" data-theme="e" data-icon="check" data-inline="true"
					href="<?= url("annonce:promote", array("id" => $annonce->id)) ?>">
					Marquer comme promue
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
			 		<h3>Candidatures</h3>
			 	</div>
			 <? if (sizeof($candidatures) == 0) : ?>
			 	<p>Aucune candidature</p>
			 <? elseif ($this->extendedProfile instanceof PorfileNiceBenevolat) : ?>  	 
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
			 		Il y a actuellement <?= sizeof($candidatures) ?> candidatures pour cette annonce.<br/>
			 		Contactez Nice Bénévolat pour en savoir davantage.
			 	</p>
			 <? endif;
		endif ?>
	</div>
	
</div>
	
<?php include("footer.php"); ?>