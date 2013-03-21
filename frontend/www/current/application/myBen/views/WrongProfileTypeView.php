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
			<h3><?= _("Mauvais type the compt") ?>e</h3>
		</div>
	
		<p>
			<?= _("Vous êtes loggué avec un compte") ?> "<?= $this->actualProfileType ?>",
		    <?= _("mais cette action nécéssite un compte") ?> "<?= $this->profileType ?>".
			<br/>
			<?= _("Merci de vous logguer avec un autre compte ou de créer un nouveau profil") ?>e :
			
		</p>
		
		<a data-role="button" data-icon="delete" data-theme="b"
				href="<?= url("logout") ?>"><?= _("Se délogguer") ?></a>
	</div>
	
	
</div>
	
<?php include("footer.php"); ?>
