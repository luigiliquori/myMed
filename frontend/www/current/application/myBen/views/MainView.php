<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); ?>

<div data-role="page" id="login">

	<? include("header-bar.php") ?>
	
	<div data-role="content">
	
		<p>Que voulez vous faire ?</p>
		
		<a 	href="<?= url("annonce:create") ?>" 
		 	style="text-align:left" 
		 	data-role="button" 
		 	data-icon="arrow-r" 
		 	data-theme="b"
		 	data-ajax="false" >Poster une annonce</a>
		
		<a 	href="<?= url("listAnnonces") ?>" 
		 	style="text-align:left" 
		 	data-role="button" 
		 	data-icon="arrow-r" 
		 	data-theme="b"
		 	data-ajax="false" >Gérer les annonces / candidatures</a>
		
		
	</div>
</div>
	
<?php include("footer.php"); ?>