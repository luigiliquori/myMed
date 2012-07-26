<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php");

/** @var $annonce Annonce */
$annonce = $this->annonce;

?>

<div data-role="page">

	<? include("header-bar.php") ?>
	
	<div data-theme="e" data-role="header" class="left" >
		<h3>Création d'une annonce</h3>
	</div>
	
	<form data-role="content" action="<?= url("annonce:doCreate") ?>" data-ajax="false" method="post" >
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" value="Poster l'annonce" />
		
	</form>
	
</div>
	
<? include("footer.php"); ?>