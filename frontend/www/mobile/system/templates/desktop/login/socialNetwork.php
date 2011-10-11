<div id="socialNetwork" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Selection</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content" data-theme="b">
	 	<?php 
	 	$socialNetworkConnection =  new SocialNetworkConnection();
	 	foreach($socialNetworkConnection->getWrappers() as $wrapper) {
	 		echo "<a href='" . $wrapper->getLoginUrl() . "'>" . $wrapper->getSocialNetworkName() . "</a>";
	 	}
	 	?>
	</div>
</div>