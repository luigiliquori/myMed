<? require("header.php"); ?>

<div data-role="page">
	<? tab_bar_main("?action=createMyMedProfile"); ?>
	<?php $this->mode = MODE_CREATE?>
	<div data-role="content" class="content">
		<!-- <form data-role="content" method="post" data-ajax="false" id="benForm" action="<?= url('createMyMedProfile:create') ?>" >-->
		<form data-role="content" method="post" data-ajax="false" id="benForm" action="?action=register" >
			
				<label for="prenom">Prénom / Activité commerciale : </label>
				<input type="text" name="prenom" value="" />
				<br />
				
				<label for="nom">Nom : </label>
				<input type="text" name="nom" value="" />
				<br />
				
				<label for="email" >eMail : </label>
				<input type="text" name="email" value="" />
				<br />
				
				<label for="password" >Mot de passe : </label>
				<input type="password" name="password" />
				<br />
				
				<label for="password" >Confirmation : </label>
				<input type="password" name="confirm" />
				<br />
				
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					J'accepte les 
					<a href="application/myMed/doc/CGU_fr.pdf" rel="external">conditions d'utilisation</a> / 
					I accept 
					<a href="application/myMed/doc/CGU_en.pdf" rel="external">the general terms and conditions</a>
				</span><br />
			<? wizard_footbar(_("Créer le profil")) ?>
		</form>
	</div>
</div>
