<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" >

	<? header_bar(array(
			"Accueil" => url("main"),
			"Annonces" => url("listAnnonces"),
			$annonce->titre => url("annonce:details", array("id" => $annonce->id)),
			"Edition" => null)) ?>
	
	<form data-role="content" data-ajax="false" method="post"
		action="<?= url("annonce:doEdit", array("id" => $annonce->id)) ?>"  >
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Enregistrer les modifications" />
		
	</form>

	
</div>
	
<?php include("footer.php"); ?>