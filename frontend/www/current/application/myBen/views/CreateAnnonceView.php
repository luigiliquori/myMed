<? include("header.php");
/** @var $annonce Annonce */
$annonce = $this->annonce;
?>

<div data-role="page">

	<!--<? header_bar(array(
			_("Annonces") => url("listAnnonces"),
			_("Création") => null)) ?>-->
			
	<? tab_bar_main("?action=main"); ?>
	<?php  include('notifications.php');?>

	
	<form data-role="content" action="<?= url("annonce:doCreate") ?>" data-ajax="false" method="post" >
		
		<? require("AnnonceForm.php") ?>
	
		<input type=submit name="submit" data-role="button" data-theme="g" value="Poster l'annonce" />
		
	</form>
	
</div>
	
<? include("footer.php"); ?>