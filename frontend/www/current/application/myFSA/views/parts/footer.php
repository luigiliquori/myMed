	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="?action=main" data-transition="none" data-icon="home" <?= $_GET['action'] == "main" || !isset($_GET['action']) ? 'data-theme="b"' : '' ?>><?= translate("Search") ?></a></li>
				<li><a data-role="button" data-transition="fade" href="?action=Publish" data-icon="plus"> <?= translate("Publish") ?></a></li>
				<li><a data-ajax="false" href="controllers/LocalisationController.php" type="button" data-transition="slide" data-icon="info"><?= translate("Localise") ?></a></li>
				<li><a href="?action=ExtendedProfile" data-icon="user" <?= $_GET['action'] == "ExtendedProfile" ? 'data-theme="b"' : '' ?> ><?= translate("Profile") ?></a></li>
			</ul>
		</div>
	</div>