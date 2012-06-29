
<?php
require_once 'Template.php';
Template::init();
?>

<!DOCTYPE html>
<html>
<head>
<?= Template::head(); ?>
</head>
<body>
	<div data-role="page" id="About" >
		<div class="wrapper">
			<div data-role="header" data-theme="c" style="max-height: 38px;">
				<h2>
					<a href="./" style="text-decoration: none;">myEurope</a>
				</h2>
			</div>
			<div data-role="content">
				<iframe src="http://docs.google.com/gview?url=http://mymed2.sophia.inria.fr/application/myEurope/myEurope.pdf&embedded=true"
					style="width: 100%; height: 800px;" frameborder="0"></iframe>
				<div class="push"></div>
			</div>
		</div>
		<?= Template::credits(); ?>
	</div>
</body>
</html>
