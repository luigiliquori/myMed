<?php

//ob_start("ob_gzhandler");
require_once 'Template.php';
Template::init();

if (!isset($_SESSION['userPerm'])){
	//complete your profile
	header("Location: ./update?extended");
}

if (isset($_GET['registration']) || (isset($_GET['userID']))) {
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
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse">about</a> 
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
				<a href="option" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']->name ?></a>
				<?php 
				}
				?>
				
			</div>
			<div data-role="content">

				<br />
				<a href="searchPartnerForm" type="button" data-theme="b" >Rechercher un partenaire</a>
				
				<a href="searchOfferForm" type="button" data-theme="b" >Rechercher une offre</a>
				
				<br />

				<a href="postPartnershipForm" class="<?= $_SESSION['userPerm']>0?"":"ui-disabled" ?>"
				type="button" data-theme="b" >Insérer un appel à partenaire</a>

				<a href="postOfferForm" class="<?= $_SESSION['userPerm']>1?"":"ui-disabled" ?>"
				type="button" data-theme="b" >Insérer une offre</a>
					
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>
