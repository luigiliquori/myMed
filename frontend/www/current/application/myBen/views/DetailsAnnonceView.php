<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	
	<div data-role="content">

	
		<a 
			href="<?= url("annonce:edit", array("id" => $annonce->id)) ?>" 
			data-ajax="false" 
			data-role="button" 
			data-theme="g"
			data-icon="edit"
			data-inline="true">Éditer</a>
		<a 
			href="<?= url("annonce:delete", array("id" => $annonce->id)) ?>" 
			data-ajax="false" 
			data-role="button" 
			data-theme="r"
			data-icon="delete"
			data-inline="true">Supprimer</a>
	
		<div data-role="header" data-theme="b">
			<h3>Details de l'annonce</h3>
		</div>
		
		<form target="#" >
			<? global $READ_ONLY; $READ_ONLY=true; ?>
			<? require("AnnonceForm.php"); ?>
		</form>
	</div>
	
</div>
	
<?php include("footer.php"); ?>