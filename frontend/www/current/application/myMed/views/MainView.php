<? include("header.php"); ?>

<div id="home" data-role="page">

<? include("header-bar.php"); ?>

	<div data-role="content" class="content">

		<div class="ui-grid-b" Style="padding: 10px;">
			<?php $column = "a"; ?>
			<?php foreach ($this->applicationList as $applicationName) { ?>
				<?php if ($this->applicationStatus[$applicationName] == "on") { ?>
					<div class="ui-block-<?= $column ?>">
						<a href="/application/<?= $applicationName ?>" rel="external" class="myIcon"><img
							alt="<?= $applicationName ?>"
							src="../../application/<?= $applicationName ?>/img/icon.png" width="50px"></a>
						<br> <span style="font-size: 9pt; font-weight: bold;"><?= $applicationName ?> </span>
					</div>
					<?php 
			    	if($column == "a") {
			    		$column = "b";
			    	} else if($column == "b") {
			    		$column = "c";
			    	} else if($column == "c") {
			    		$column = "a";
			    		echo '</div><br /><div class="ui-grid-b">';
			    	}
				}   	 
			} ?>
		</div>
		
	</div>

	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-back="true" data-icon="grid" class="ui-btn-active ui-state-persist">Applications</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
				<li><a href="#store" data-transition="none" data-icon="star">Store</a></li>
<!-- 				<li><a href="#" data-rel="dialog" data-icon="star" -->
<!-- 					onClick="printDialog('hidden-sharethis', 'Partagez');">Partagez</a> -->
<!-- 				</li> -->
			</ul>
		</div>
	</div>

</div>

			<? include("ProfileView.php"); ?>
			<? include("UpdateProfileView.php"); ?>
			<? include("StoreView.php"); ?>

			<? include("footer.php"); ?>