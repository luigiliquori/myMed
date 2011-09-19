<div id="socialNetwork" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Selection</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content" id="one" data-theme="b">
		<?php 
			$connexion = GlobalConnexion::getInstance();
			$connexion->button();
		?>
	</div>
</div>