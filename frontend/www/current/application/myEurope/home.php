<?php

define('APP_ROOT', __DIR__);
//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();
Template::checksession();
if (isset($_SESSION['profile'])){

	if ($_SESSION['profile']->permission <= 0){
		header("Location: ./pending");
	}
} else {


	$_SESSION['profile'] = new stdClass(); //@TODO create profile class
	//complete your profile
	$extProfile = Template::fetchMyProfile();

	if($extProfile->status == 200 ) {
		foreach ($extProfile->dataObject->details as $v){
			if ($v->key == "role")
				$_SESSION['profile']->role = $v->value;
			else if ($v->key == "permission")
				$_SESSION['profile']->permission = $v->value;
		}
		
		if ($_SESSION['profile']->permission <= 0){
			header("Location: ./pending");
		}
		
	} else {
		header("Location: ./updateExtended?new");
	}
}


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="Home" data-theme="d">
		<div data-role="popup" id="popupShare" data-overlay-theme="b" data-theme="c" class="ui-corner-all">
			<div style="text-align:center;">
				<span class="st_googleplus_large"></span>
				<span class="st_facebook_large"></span>
				<span class="st_twitter_large"></span>
				<span class="st_sharethis_large"></span>
				<span class="st_email_large"></span>
			</div>
		</div>
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="d" data-iconpos="left">
				<ul>
					<li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" rel="external" data-icon="delete" data-iconpos="notext">myMed</a></li>
					<li><a href="about" data-transition="slidefade" data-icon="info" data-direction="reverse"><?= _('About') ?></a></li>
					<li><a href="" data-icon="home" class="ui-btn-active ui-state-persist"><?= _('Home') ?></a></li>
					<li><a href="#popupShare" data-icon="star" data-rel="popup">Partager</a></li>
					<li><a href="option" data-icon="profile" data-transition="slidefade"><?= _('Profil') ?></a></li>
				</ul>
			</div>
		</div>
		<?php 
		if ($_SESSION['profile']->permission > 1){
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
		
		<div data-role="content" style="text-align:center;">

			<h1 class="ui-link">myEurope</h1>
	
			<h3 class="ui-link">Partenariats:</h3>
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchPartnerForm" type="button" data-theme="e" style="width:50%;"><br />Rechercher un partenariat<br />&nbsp;</a>
				<a href="postPartnershipForm" style="width:49%;" type="button" data-theme="e" rel="external"><br />Déposer une offre de partenariat<br />&nbsp;</a>
			</div>
			
			<h3 class="ui-link">Informations:</h3>
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchInfoForm" style="width:33%;" type="button"  data-theme="d"><br />S'informer sur les programmes 2014-2020<br />&nbsp;</a>
				<a href="postInfoForm"  style="width:33%;"
				type="button"  data-theme="d"><br />Blog sur la préparation du futur programme Alcotra 2014-2020<br />&nbsp;</a>
				<a href="postInfoForm"  style="width:33%;"
				type="button"  data-theme="d"><br />Blog béta testeurs<br />&nbsp;</a>
			</div>

		</div>



		
	</div>
</body>
</html>
