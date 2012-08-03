<? include("header.php"); ?>


<!-- Header -->
<div data-role="header" data-theme="c" data-position="fixed">
	<? tabs_3(
			"Profil",
			"Valider",
			"document.ExtendedProfileForm.submit();",
			"check") ?>
	<? include("notifications.php")?>
</div>

<div data-role="content">
	<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm" data-ajax="false" class="compact">
		<input type="hidden" name="form" value="edit" />

		<label for="textinputu0" class="label"> Rôle: </label>
		<input id="textinputu0" name="role" placeholder="" value='<?= $_SESSION['myEuropeProfile']->role ?>' type="text" />
			
		<label for="textinputu1" class="label"> Nom: </label>
		<input id="textinputu1" name="name" placeholder="" value='<?= $_SESSION['myEuropeProfile']->name ?>' type="text" />
		
		<label for="textinputu2" class="label"> Domaine d'action: </label>
		<input id="textinputu2" name="activity" placeholder="" value='<?= $_SESSION['myEuropeProfile']->activity ?>' type="text" />
		
		<label for="textinputu3" class="label"> N°SIRET: </label>
		<input id="textinputu3" name="siret" placeholder="" value='<?= $_SESSION['myEuropeProfile']->siret ?>' type="text" />
		
		<label for="textinputu4" class="label"> Adresse: </label>
		<input id="textinputu4" name="address" placeholder="" value='<?= $_SESSION['myEuropeProfile']->address ?>' type="text" />

		<label for="textinputu5" class="label"> Email: </label>
		<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['myEuropeProfile']->email ?>' type="email" />
		
		<label for="textinputu6" class="label"> Téléphone: </label>
		<input id="textinputu6" name="phone" placeholder="" value='<?= $_SESSION['myEuropeProfile']->phone ?>' type="text" />
		
		<label for="textinputu7" class="label"> Description: </label>
		<input id="textinputu7" name="desc" placeholder="" value='<?= $_SESSION['myEuropeProfile']->desc ?>' type="text" />
		
		<br />
		<label for="password" ><?= _("Mot de passe") ?></label>
		<input type="password" name="password" />
		
		<div style="text-align: center;" >
			<input type="submit" data-inline="true" data-role="button" value="Valider"/>
		</div>
	</form>
</div>
<? include("footer.php"); ?>