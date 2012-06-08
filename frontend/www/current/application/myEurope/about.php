
<?php
	require_once 'Template.class.php';
	$template = new Template();
?>

<!DOCTYPE html>
<html>
	<head>
		<?= $template->head(); ?>
	</head>
	<body>
		<div data-role="page" id="About">
			<div class="wrapper">
				<div data-role="header" data-theme="b">
					<a href="./" data-icon="home" class="ui-btn-right" data-iconpos="notext" data-transition="slide"> Accueil </a>
					<h3>myEurope - about</h3>
				</div>
				<div data-role="content">
					<iframe src="http://docs.google.com/gview?url=http://mymed2.sophia.inria.fr/application/myEurope/myEurope.pdf&embedded=true" style="width:100%; height:800px;;" frameborder="0"></iframe>
					<div class="push"></div>
				</div>
			</div>
			<?= $template->credits(); ?>
		</div>
	</body>
</html>