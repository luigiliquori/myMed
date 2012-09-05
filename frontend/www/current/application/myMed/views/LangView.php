<div id="Lang" data-role="page">
<? include("header-bar.php"); ?>
	<div data-role="content" style="text-align:center;">
		<br /><br />
		<?= _("Choose a language") ?>:<br />
		<fieldset data-role="controlgroup" data-type="horizontal" style="display:inline-block;vertical-align: middle;">
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-a" value="fr" <?= $_SESSION["user"]->lang == "fr"?"checked='checked'":"" ?>/>
			<label for="radio-view-a"><?= _("French") ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-b" value="it" <?= $_SESSION["user"]->lang == "it"?"checked='checked'":"" ?>/>
			<label for="radio-view-b"><?= _("Italian") ?></label>
			<input onclick="updateProfile('lang', $(this).val());" type="radio" name="name" id="radio-view-e" value="en" <?= $_SESSION["user"]->lang == "en"?"checked='checked'":"" ?>/>
			<label for="radio-view-e"><?= _("English") ?></label>
		</fieldset>
		<br />
		<div style="text-align: center;" >
			<a href="index.php?action=main#profile" type="button" data-inline="true" data-ajax="false"><?= _("Envoyer") ?></a>
		</div>
	</div>
</div>