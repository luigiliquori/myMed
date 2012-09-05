<? include("header.php");

// Alias
$candidature = $this->candidature;
$annonce = $candidature->getAnnonce();
?>

<div data-role="page">


	<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			_("Candidater") => null)) ?>
	
	<div data-theme="e" data-role="header" class="left" >
		<h3><?= sprintf(_("Réponse à l'annonce '%s'"), $annonce->titre) ?></h3>
	</div>
	
	<form data-role="content" action="<?= url("candidature:doCreate") ?>" data-ajax="false" method="post" >
		
		<input type="hidden" name="annonceID" value="<?= $candidature->annonceID ?>" />
		
		<? input("textarea", "message", _("Message"), $candidature->message) ?>
		
		<input type="submit" name="submit" data-role="button" data-theme="g" value="<?= _("Poster la candidature") ?>" />
		
	</form>
	
</div>
	
<? include("footer.php"); ?>