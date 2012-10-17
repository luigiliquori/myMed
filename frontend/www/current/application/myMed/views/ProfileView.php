<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<div id="profile" data-role="page" data-dom-cache="true">

	<?php print_header_bar(false, true) ?>
	<?php include 'notifications.php'; ?>

	<div data-role="content">

		<ul data-role="listview" data-mini="true">
			<li data-role="list-divider"><?= translate("About you") ?></li>
			<li data-icon="pencil"><a href="?action=profile#updateProfile"><?php if($_SESSION['user']->profilePicture != "") { ?>
					<img class="ui-li-mymed" alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="60" height="60">
				<?php } else { ?>
					<img class="ui-li-mymed" alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="60" height="60">
				<?php } ?>
				<?= $_SESSION['user']->name ?></a>
			</li>

			<li data-icon="pencil"><a href="?action=profile#updateProfile"><img class="ui-li-mymed" alt="eMail: " src="<?= APP_ROOT ?>/img/email_icon.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> Email
				<p>
					<?= $_SESSION['user']->email ?>
				</p>
				</a>
			</li>
			<li data-icon="pencil"><a href="?action=profile#updateProfile"><img class="ui-li-mymed" alt="Date de naissance: " src="<?= APP_ROOT ?>/img/birthday_icon.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> Date de naissance
				<p>
					<?= $_SESSION['user']->birthday ?>
				</p>
				</a>
			</li>
			<li data-icon="pencil"><a href="?action=profile#updateProfile"><img class="ui-li-mymed" alt="Langue: " src="<?= APP_ROOT ?>/img/<?= $_SESSION['user']->lang ?>_flag.png" width="50"
				Style="margin-left: 5px; top: 5px;" /> Langue
				<p>
					<?= $_SESSION['user']->lang ?>
				</p>
				</a>
			</li>

			<li data-role="list-divider"><?= translate("You and your applications") ?></li>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) { ?>
			<?php if ($status == "on") { ?>
			<li><a href="<?= APP_ROOT ?>/../<?= $applicationName ?>/index.php?action=extendedProfile" rel="external"> <img class="ui-li-mymed"
					alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" width="50" Style="margin-left: 5px; top: 5px;" /> <?= $applicationName ?>
					<div Style="position: relative; left: 0px;">
						<?php for($i=1 ; $i <= 5 ; $i++) { ?>
						<?php if($i*20-20 < $_SESSION['reputation'][$applicationName . EXTENDED_PROFILE_PREFIX] ) { ?>
						<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
						<?php } else { ?>
						<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
						<?php } ?>
						<? } ?>
					</div>
			</a>
			</li>
			<?php } 
		    } ?>
	    </ul>
		
		<br /><br />
	</div>

	<? print_footer_bar_main("?action=profile"); ?>
	
</div>

<? include_once 'UpdateProfileView.php'; ?>

<? include_once 'footer.php'; ?>

