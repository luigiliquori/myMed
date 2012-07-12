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
</head>

<body>
	<div data-role="page" id="Home">

		<div data-role="header" data-theme="c" style="max-height: 38px;">
			
			
			<div data-role="controlgroup"  data-type="horizontal" class="ui-btn-left">
				<a href="http://<?= $_SERVER['HTTP_HOST'] ?>" type="button" data-icon="home">Quitter</a>
				<a href="#About" type="button" data-inline="true" ><?= _('About') ?></a><br />
			</div>
			
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
			<?php 
			if ($_SESSION['user']->profilePicture){
			?>
			<span><a href="option" data-transition="slide"><img class="ui-btn-right" style="max-height: 36px; top:1px" src="<?= $_SESSION["user"]->profilePicture ?>"
			 alt="<?= $_SESSION['user']->name ?>" title="<?= $_SESSION['user']->name ?>"/></a></span>
			<?php 
			} else {
			?>
			<a href="option" class="ui-btn-right" data-transition="slide" data-icon="profile" data-iconpos="right"><?= $_SESSION['user']->name ?></a>
			<?php 
			}
			?>
			
		</div>
		<div data-role="content">

			<br />
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchOfferForm" style="width:50%;" type="button" data-theme="b" >Projets soumis</a>
				<a href="postOfferForm"  style="width:49%;" class="<?= $_SESSION['userPerm']>1?"":"ui-disabled" ?>"
				type="button" data-theme="b" >Publier un projet<?= $_SESSION['userPerm']>1?"":" (*)" ?></a>
			</div>
			
			<br />
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchPartnerForm" type="button" data-theme="i" style="width:50%;">Rechercher un partenariat</a>
				<a href="postPartnershipForm" style="width:49%;" class="<?= $_SESSION['userPerm']>0?"":"ui-disabled" ?>"
				type="button" data-theme="i" >Publier un partenariat<?= $_SESSION['userPerm']>0?"":" (*)" ?></a>
			</div>
			
			
			
			<br />
			<div data-role="controlgroup"  data-type="horizontal">
				<a href="searchInfoForm" style="width:50%;" type="button" data-theme="c" >S'informer sur les programmes</a>
				<a href="postInfoForm"  style="width:49%;"
				type="button" data-theme="c" >Commenter</a>
			</div>
			
			<?php 
			if ($_SESSION['userPerm']==0){
			?>
			<br /><br />
			<div style="width:45%;margin-left: auto;margin-right: auto;text-align:center;">
			(*) Accès restreint, aux logins *@inria.fr en phase alpha<br />
			Veuillez <a href="mailto:mymeddev@gmail.com">nous contacter</a>, pour obtenir la permission de publier:
			
<!-- 				<form action="post"> -->
<!-- 				<input name="joinrequest" type="hidden" value="on"> -->
<!-- 				<textarea name="content"></textarea> -->
<!-- 				<input type="submit" data-mini="true" data-inline="true" data-theme="b" value="Envoyer" /> -->
<!-- 				</form> -->
			</div>
			
			<?php 
			}
			?>

		</div>
		<?= Template::footer(); ?>
	</div>
</body>
</html>
