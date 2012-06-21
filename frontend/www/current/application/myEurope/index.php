<?php

	//ob_start("ob_gzhandler");
	session_start();

	if (isset($_GET['registration']) || (isset($_GET['userID']))) {
		header("Location: ./option?".$_SERVER['QUERY_STRING']);
	} else if (!isset($_SESSION['user'])) {
		header("Location: ./authenticate");
	}
	require_once 'Template.class.php';
	$template = new Template();
	
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>

	<body>
		<div data-role="page" id="Home">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<h2></h2>
					<a href="about" data-theme="b" type="button" data-icon="info" data-transition="slide" data-direction="reverse" >about</a>
					<a href="post" type="button" class="ui-btn-right" data-theme="d" style="position: absolute;left: 40%;width:20%;">Insérer</a>
					<a id="opt" href=<?= $_SESSION['user']?"option":"authenticate" ?> class="ui-btn-right" data-transition="slide"><?= $_SESSION['user']?$_SESSION['user']->name:"Connexion" ?></a>
				</div>
				<div data-role="content">	
					<div style="margin-top:2em; margin-bottom:auto;">
					<h1 style="text-align: center;color: #0060AA;font-size:350%;">myEurope</h1>
					<form action="search" id="subscribeForm">
						<input name="q" placeholder="chercher un partenaire par mot clés" value="" data-type="search"/>
						<input name="type" value="partenaires" type="hidden" />
					</form>
					<br />
					<span style="margin:5px 15px;">ou</span>
					<form action="search" id="subscribeForm2">
						<input name="q" placeholder="chercher un appel d'offre par mot clés" value="" data-type="search"/>
						<input name="type" value="offres" type="hidden" />
					</form>
					</div>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>
