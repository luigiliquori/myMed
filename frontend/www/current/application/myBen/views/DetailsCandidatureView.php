<? include("header.php");

// Alias
$candidature = $this->candidature;
$annonce = $candidature->getAnnonce();
$candidat = $candidature->getPublisher();
$profCandidat = ProfileBenevole::getFromUserID($candidature->publisherID);

?>

<div data-role="page">

	<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			_("Candidature de " . $candidature->publisherName) => null)) ?>
	
	<? global $READ_ONLY; $READ_ONLY=true ?>
	<form data-role="content" action="#" >
		
		<div data-role="header" data-theme="b">
			 <h3><?= _("Candidat") ?></h3>
		</div>
		
		<? input("text", "name", "Nom", $candidature->publisherName) ?>
		
		<? input("email", "email", "Email", $candidat->email) ?>
		
		<? input("tel", "tel", "Teléphone", $profCandidat->tel) ?>
		
		<a data-role="button" data-icon="person" data-inline="true" data-ajax="false"
		    href="<?= url("extendedProfile:show", array("id" => $candidature->publisherID)) ?>">
            <?= _("Voir le profil complet") ?>
        </a>
		 
		<div data-role="header" data-theme="b">
			 <h3><?= _("Candidature") ?></h3>
		</div>
		
		<? input("date", "date", "Date", $candidature->begin) ?>
		
		<? input("textarea", "message", "Message", $candidature->message) ?>
		
	</form>
	
</div>
	
<? include("footer.php"); ?>
