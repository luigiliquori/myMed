<? 

//
// This view shows both the login and register forms, with two tabs
//
include("header.php"); 
$annonce = $this->annonce;
?>

<div data-role="page" >

	<? require("header-bar.php")?>
	<div data-role="content" >
	
		<div data-theme="e" data-role="header" >
			<h3>Mauvais type the compte</h3>
		</div>
	
		<p>
			Vous êtes loggué avec un compte "<?= $this->actualProfileType ?>",
			mais cette action nécéssite un compte "<?= $this->profileType ?>".
			<br/>
			Merci de vous logguer avec un autre compte ou de créer un nouveau profile :
			
		</p>
		
		<a data-role="button" data-icon="delete" data-theme="b"
				href="<?= url("logout") ?>">Se délogguer</a>
	</div>
	
	
</div>
	
<?php include("footer.php"); ?>