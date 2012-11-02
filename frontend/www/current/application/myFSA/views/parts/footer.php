	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="?action=main" data-transition="none" data-icon="home" <?= !isset($_GET['action']) || $_GET['action'] == "details"|| $_GET['action'] == "search" ? 'data-theme="b"' : '' ?>><?= translate("Search") ?></a></li>
				<?php if (isset($_SESSION["profileFilled"]) && $_SESSION["profileFilled"] != "guest") {?>
				<li><a data-role="button" data-transition="fade" href="?action=Publish" data-icon="plus" <?= isset($_GET['action']) && $_GET['action'] == "Publish" ? 'data-theme="b"' : '' ?>> <?= translate("Publish") ?></a></li>
				<?php } ?>
				<li><a data-ajax="false" href="controllers/LocalisationController.php" type="button" data-transition="slide" data-icon="info"><?= translate("Localise") ?></a></li>
				<li><a href="?action=ExtendedProfile" data-icon="user" <?= $_GET['action'] == "ExtendedProfile" ? 'data-theme="b"' : '' ?> ><?= translate("Profile") ?></a></li>
			</ul>
		</div>
	</div>