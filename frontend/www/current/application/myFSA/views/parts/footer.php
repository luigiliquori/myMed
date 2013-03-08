	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="?action=main" data-transition="none" data-icon="home" <?= !isset($_GET['action']) || $_GET['action'] == "main" ? 'data-theme="b"' : '' ?>><?= translate("Home") ?></a></li>
				<li><a data-role="button" data-transition="none" href="?action=Search" data-icon="search" <?= isset($_GET['action']) && ($_GET['action'] == "details"|| $_GET['action'] == "Search") ? 'data-theme="b"' : '' ?>> <?= translate("Search") ?></a></li>
				<?php if (isset($_SESSION["profileFilled"]) && $_SESSION["profileFilled"] != "guest") {?>
				<li><a data-role="button" data-transition="fade" href="?action=Publish" data-icon="plus" <?= isset($_GET['action']) && $_GET['action'] == "Publish" ? 'data-theme="b"' : '' ?>> <?= translate("Publish") ?></a></li>
				<?php } ?>
				<li><a data-ajax="false" href="?action=Localise" type="button" data-transition="slide" data-icon="info"><?= translate("Localize") ?></a></li>
				<li><a href="?action=ExtendedProfile" data-icon="user" <?= isset($_GET['action']) && $_GET['action'] == "ExtendedProfile" ? 'data-theme="b"' : '' ?> ><?= translate("Profile") ?></a></li>
			</ul>
		</div>
	</div>