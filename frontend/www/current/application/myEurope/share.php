<?php

define('APP_ROOT', __DIR__);
//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
<!-- Share this -->
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "5d7eff17-98e8-4621-83ba-6cb27a46dd05"}); </script>
</head>

<body>
	<div data-role="page" id="Home" data-theme="c">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="d" data-iconpos="left">
				<ul>
					<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
					<li><a href="about" data-transition="slidefade" data-icon="info" data-direction="reverse"><?= _('About') ?></a></li>
					<li><a href="./" data-icon="home" data-transition="slidefade"  data-direction="reverse"><?= _('Home') ?></a></li>
					<li><a href="share" data-icon="plus" class="ui-btn-active ui-state-persist" > Partager</a></li>
					<li><a href="option" data-icon="profile" data-transition="slidefade"><?= _('Profil') ?></a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			
			<h2 style="text-align:center;">
				<a href="#" style="text-decoration: none;">Partager sur un réseau social:</a>
			</h2>
			
			<br />
			<div style="text-align:center;">
				<span class="st_googleplus_large"></span>
				<span class="st_facebook_large"></span>
				<span class="st_twitter_large"></span>
				<span class="st_sharethis_large"></span>
				<span class="st_email_large"></span>
			</div>

		</div>



		<?php 
		if ($_SESSION['profile']->permission>0){
		?>
		<div data-role="footer" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-iconpos="left">
				<ul>
					<li><a href="admin" data-icon="gear" rel="external" data-theme="d" data-transition="slidefade">Admin</a></li>
				</ul>
			</div>
		</div>
		
		<?php 
		}
		?>
	</div>
</body>
</html>
