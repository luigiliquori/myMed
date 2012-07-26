<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" >

	<? include("header-bar.php") ?>
	
	<div data-theme="e" data-role="header" class="left" >
		<h3>Edition d'une annonce</h3>
	</div>
	
	<form data-role="content" data-ajax="false" method="post"
		action="<?= url("annonce:doEdit", array("id" => $annonce->id)) ?>"  >
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" 
			value="Enregistrer les modifications" />
		
	</form>

	
</div>
	
<?php include("footer.php"); ?>