<div id="socialNetwork" data-role="page" data-theme="c">
	<!-- HEADER -->
	<div id="header" data-role="header" >
		<h2>Connectez-vous avec votre compte:</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content" style="text-align: center;">
	 	<?php 
	 	$socialNetworkConnection =  new SocialNetworkConnection();
	 	foreach($socialNetworkConnection->getWrappers() as $wrapper) {
	 		echo "<a href='" . $wrapper->getLoginUrl() . "'>" . $wrapper->getSocialNetworkButton() . "</a>";
	 	}
	 	?>
	</div>
</div>