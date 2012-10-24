<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<div id="home" data-role="page" data-dom-cache="true">
	
	<?php print_header_bar(false, true) ?>
	<?php include('notifications.php'); ?>
	
	<div data-role="content" style="text-align: center;">
		<br />
		<br />
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

	<? print_footer_bar_main("?action=main"); ?>
	
</div>

<? include_once 'footer.php'; ?>
