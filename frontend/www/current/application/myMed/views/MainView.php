<? include("header.php"); ?>

<div id="home" data-role="page">

	
	<? tab_bar_main("?action=main"); ?>
	<? include("notifications.php"); ?>
	

	<div data-role="content" style="text-align: center;">
		<br />
		<br /><? debug_r($_SESSION['user2']);debug_r($_SESSION['user']);debug_r($_SESSION['user3']); ?>
		<div class="ui-grid-b">
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) { ?>
				<?php if ($status == "on") { ?>
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



</div>
<? include("ProfileView.php"); ?>

<? include("footer.php"); ?>