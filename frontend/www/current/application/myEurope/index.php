<!DOCTYPE html>
<html>

<?php
	
	//ob_start("ob_gzhandler");
	session_start();

	if (!isset($_SESSION['user'])) {
		header("Location: ./authenticate");
	} else if (isset($_GET['registration']) || (isset($_GET['userID']))) {
		header("Location: ./option?".$_SERVER['QUERY_STRING']);
	}
	
	require_once 'Template.class.php';
	$template = new Template();
	
?>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
		<div data-role="page" id="Home">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<h2></h2>
					<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse">about</a>
					<a href="post" type="button" data-icon="gear" class="ui-btn-right" style="position: absolute;left: 30%;right: 30%;width:20%;">Soumettre</a>
					<a href=<?= $_SESSION['user']?"option":"authenticate" ?> data-icon="arrow-r" class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">	
					
					
					<div style="margin-top:3em; margin-bottom:auto;">
					<h1 style="color:DarkBlue;text-align: center;">myEurope</h1>
					<br />
					<form action="search" id="subscribeForm">
						<input name="q" placeholder="chercher un partenaire par mot clés" value="" data-type="search" style="width: 80%;"/>
					</form>
					</div>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
