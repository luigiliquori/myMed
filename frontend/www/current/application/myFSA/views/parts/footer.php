	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar">
			<ul>
				<li><a href="?action=main" data-transition="none" data-icon="home" <?= $_GET['action'] == "main" || !isset($_GET['action']) ? 'data-theme="b"' : '' ?>><?= translate("Home") ?></a></li>
				<li><a href="?action=ExtendedProfile" data-icon="user" <?= $_GET['action'] == "ExtendedProfile" ? 'data-theme="b"' : '' ?> ><?= translate("Profile") ?></a></li>
			</ul>
		</div>
	</div>