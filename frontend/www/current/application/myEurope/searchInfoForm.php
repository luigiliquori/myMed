
<?php

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
	<div data-role="page" id="SearchInfo">
		<div data-role="header" data-theme="c" data-position="fixed">
			<div data-role="navbar" data-theme="c"  data-iconpos="left">
				<ul>
					<li><a data-rel="back" data-icon="back">Retour</a></li>
					<li><a data-icon="search" data-theme="b" onclick="$('#searchForm').submit();">Chercher</a></li>
				</ul>
			</div>
		</div>
		<div data-role="content">
			todo
		</div>
	</div>
</body>
</html>
