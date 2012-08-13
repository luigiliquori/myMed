<?php

define('APP_ROOT', __DIR__);
//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();
?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="Home" data-theme="d">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
					<li><a href="about" data-icon="info"  data-transition="slidefade" data-direction="reverse"><?= _('About') ?></a></li>
					<li><a href="" data-icon="home"  class="ui-btn-active ui-state-persist"><?= _('Home') ?></a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h1 style="text-align:center;">
				<a href="#" style="text-decoration: none;"><?= Template::APPLICATION_NAME ?></a>
			</h1>

			<h3 style="text-align:center;">
				<a href="#" style="text-decoration: none;">Un email a été envoyé à "myEurope". Votre profil est en attente de validation.</a>
			</h3>
			

		</div>
	</div>
</body>
</html>
