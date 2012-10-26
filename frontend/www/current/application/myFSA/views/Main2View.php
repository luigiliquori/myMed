<? include("header.php"); ?>


<div data-role="page" data-theme="b">
<? include("header-bar.php"); ?>


 	<div data-role="content" style="padding: 15px" data-ajax="false">
	<a data-ajax="false" href="controllers/LocalisationController.php" type="button" data-transition="slide" ><?= translate("Localise") ?></a>
	<form action="index.php?action=publish" method="POST" data-ajax="false">
	<input type="hidden" name="method" value="Rechercher" />
	<input type="submit" value="<?= translate('Search') ?>" />
	</form>
	</div>
	
<? include("footer.php"); ?>
</div>
</body>