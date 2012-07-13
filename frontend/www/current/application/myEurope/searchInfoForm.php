
<?php

//ob_start("ob_gzhandler");

require_once 'Template.php';
Template::init();


?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>

<body>
	<div data-role="page" id="SearchOffer">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back">Retour</a></li>
					<li><a data-icon="search" data-theme="b" onclick="$('#searchForm').submit();">Chercher</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			<h1 style="text-align:center;">
				<a href="" style="text-decoration: none;">S'informer sur un programme par thématique:</a>
			</h1>
			<br />
			<a type="button" href="#Environnement">Environnement</a>
			<a type="button" href="#Energie">Energie</a>
			<a type="button" href="#Education">Education</a>
		</div>
	</div>
	<div data-role="page" id="Environnement">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			<h1 style=" font-size: 200%; text-align: center;">S'informer sur un programme:</h1>
			<br />
			<a type="button" href="#Life+">Life+</a>
			<a type="button" href="#Marco Polo">Marco Polo</a>
		</div>
	</div>
	<div data-role="page" id="Life+">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			test
		</div>
	</div>
	<div data-role="page" id="Marco Polo">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			tst
		</div>
	</div>
	<div data-role="page" id="Energie">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			<h1 style=" font-size: 200%; text-align: center;">S'informer sur un programme:</h1>
			<br />
			<a type="button" href="#Energie0">Energie</a>
			<a type="button" href="#Energie1">Education</a>
		</div>
	</div>
	<div data-role="page" id="Education">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			<h1 style=" font-size: 200%; text-align: center;">S'informer sur un programme:</h1>
			<br />
			Objectifs:
			...
			<br />
			<a type="button" href="#Education-llp">Education et Formation tout au long de la vie (LLP)</a>
			<a type="button" href="#Edu-2">Education</a>
		</div>
	</div>
	<div data-role="page" id="Education-llp">
		<div data-role="header" data-theme="c" style="max-height: 38px;">
			<a data-icon="back" data-rel="back">Retour</a>
			<h2>
				<a href="./" style="text-decoration: none;">myEurope</a>
			</h2>
		</div>
		<div data-role="content">
			<br />
			boom
		</div>
	</div>
</body>
</html>
