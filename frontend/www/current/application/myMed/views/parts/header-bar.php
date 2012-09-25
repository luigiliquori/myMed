<div data-role="header" data-theme="b" data-position="fixed">
	
	<!-- SHARE THIS -->
	<div style="display: none;">
		<div id="hidden-sharethis">
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
			<span class='st_linkedin_large' displayText='LinkedIn'></span>
			<span class='st_email_large' displayText='Email'></span>
		</div>
	</div>
	
	<?php if(isset($_REQUEST["applicationStore"])) { ?>
		<a href="#" data-rel="back" data-icon="arrow-l">Retour</a>
	<?php } ?>
	
	<h1><?= APPLICATION_NAME ?></h1>
	
	<a href="?action=logout" data-inline="true" rel="external" data-role="button" data-icon="delete" data-theme="r" data-icon="power" class="ui-btn-right">Déconnexion</a>
	
	<? include("notifications.php")?>
	
</div>