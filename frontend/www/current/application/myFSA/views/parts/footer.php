	<div data-role="footer" data-position="fixed" data-theme="d">
		<div data-role="navbar" data-iconpos="left" >
			<ul>
				<li><a href="?action=main" data-transition="none" data-icon="home" <?= !isset($_GET['action']) || $_GET['action'] == "main" ? 'data-theme="b"' : '' ?>><?= _("Home") ?></a></li>
			 
			 <? if(!isset($_SESSION['user']) || $_SESSION['user']->is_guest): ?>
					<li><a data-role="button" data-transition="none" href="?action=login" data-icon="signin" <?= isset($_GET['action']) && ($_GET['action'] == "login" || $_GET['action'] == "register") ? 'data-theme="b"' : '' ?>> <?= _("Sign in") ?></a></li>
			 <? endif; ?>
				
				<li><a data-role="button" data-transition="none" href="?action=Search" data-icon="search" <?= isset($_GET['action']) && ($_GET['action'] == "details"|| $_GET['action'] == "Search") ? 'data-theme="b"' : '' ?>> <?= translate("Search") ?></a></li>
		 	 
		 	 <? if (isset($_SESSION['ExtendedProfile']) && $_SESSION["profileFilled"] != "guest") {?>
				<li><a data-role="button" data-transition="fade" href="?action=Publish" data-icon="plus" <?= isset($_GET['action']) && $_GET['action'] == "Publish" ? 'data-theme="b"' : '' ?>> <?= translate("Publish") ?></a></li>
			 <? } ?>
			 
				<li><a data-ajax="false" href="?action=Localise" type="button" data-transition="slide" data-icon="info"><?= translate("Localize") ?></a></li>
			 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest): ?>
					<li><a href="?action=ExtendedProfile" data-icon="user" <?= isset($_GET['action']) && $_GET['action'] == "ExtendedProfile" ? 'data-theme="b"' : '' ?> ><?= translate("Profile") ?></a></li>
			 <? endif; ?>	
			 <? if (!isset($_SESSION['ExtendedProfile'])): ?>
					<li><a href="?action=About" data-transition="none" data-icon="info" <?= isset($_GET['action']) && $_GET['action'] == "About" ? 'data-theme="b"' : '' ?>><?= _("About")?></a></li>
			 <? endif; ?>
			</ul>
		</div>
	</div>