<div id="socialNetwork" data-role="page" data-theme="c">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b">
		<a href="#loginView" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		<h3> </h3>
	</div>

	<!-- CONTENT -->
	<div data-role="content" style="text-align: center;">
		<h3>Connectez-vous avec votre compte:</h3>
		
	 	<?php 
	 	$socialNetworkConnection =  new SocialNetworkConnection();
	 	foreach($socialNetworkConnection->getWrappers() as $wrapper) {
	 		echo "<a href='" . $wrapper->getLoginUrl() . "' onClick='showLoadingBar(\"redirecton en cours...\")'>" . $wrapper->getSocialNetworkButton() . "</a>";
	 	}
	 	?>
	    
	</div>
</div>