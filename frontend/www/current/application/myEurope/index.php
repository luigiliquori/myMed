<?php

define('APP_ROOT', __DIR__);
//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

if (!isset($_SESSION['userPerm'])){
	//complete your profile
	$extProfile = Template::fetchExtProfile();
		
	if($extProfile->status == 200 ) {
		foreach ($extProfile->dataObject->details as $v){
			if ($v->key == "type")
				$_SESSION['userType'] = $v->value;
			else if ($v->key == "perm")
				$_SESSION['userPerm'] = $v->value;
		}
	} else {
		header("Location: ./update?extended");
	}
}

if (isset($_GET['registration'])) {
	header("Location: ./authenticate?".$_SERVER['QUERY_STRING']);
} else if (isset($_GET['userID'])) {
	header("Location: ./option?".$_SERVER['QUERY_STRING']);
}

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
	<div data-role="page" id="Home">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-iconpos="left">
				<ul>
					<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
					<li><a href="about" data-transition="slidefade" data-icon="info" data-direction="reverse"><?= _('About') ?></a></li>
					<li><a onclick="$('#shareThis_').hide();" href=""  data-icon="home" class="ui-btn-active ui-state-persist"><?= _('Home') ?></a></li>
					<li><a onclick="$('#shareThis_').toggle();" data-icon="plus"> Partager</a>
					</li>
					<li><a href="option" data-icon="profile" data-transition="slidefade"><?= _('Profil') ?></a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<div id="shareThis_" style="position:absolute;top:16px;right:21%;z-index: 70000;opacity: .7;display:none;">
				<span class="st_googleplus_large"></span>
				<span class="st_facebook_large"></span>
				<span class="st_twitter_large"></span>
				<span class="st_sharethis_large"></span>
				<span class="st_email_large"></span>
			</div>
			<h1 style="text-align:center;">
				<a href="#" style="text-decoration: none;"><?= Template::APPLICATION_NAME ?></a>
			</h1>
			
			<br />
			<h3 style="text-align:center;">
				<a href="#" style="text-decoration: none;">Partenariats: ...</a>
			</h3>
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchPartnerForm" type="button" data-theme="d" style="width:50%;">Rechercher</a>
				<a href="postPartnershipForm" style="width:49%;" type="button" data-theme="c">Insérer</a>
			</div>

			<br />
			<h3 style="text-align:center;">
				<a href="#" style="text-decoration: none;">Informations: ...</a>
			</h3>
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchInfoForm" style="width:50%;" type="button" data-theme="c" >Programmes</a>
				<a href="postInfoForm"  style="width:49%;"
				type="button" data-theme="c" >Commentaires</a>
			</div>

		</div>



		<?php 
		if ($_SESSION['userPerm']>0){
		?>
		<div data-role="footer" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c" data-iconpos="left">
				<ul>
					<li><a href="admin" data-icon="gear" data-transition="slidefade">Admin</a></li>
				</ul>
			</div>
		</div>
		
		<?php 
		}
		?>
	</div>
</body>
</html>
