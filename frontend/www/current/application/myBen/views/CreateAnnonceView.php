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
	
	<form data-role="content" action="<?= url("annonce:doCreate") ?>" data-ajax="false" method="post" >
	
		<div data-role="header" data-theme="b">
			<h3>Description de l'annonce</h3>
		</div>
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" value="Poster l'annonce" />
		
	</form>
	
</div>
	
<? include("footer.php"); ?>