<?php

require_once("header.php");
require_once("header-bar.php");
require_once("footer-bar.php");

?>

<div id="home" data-role="page" data-dom-cache="true">
	
	<? if (isset($_SESSION['user']->is_guest)): ?>
		<? tab_bar_main("?action=main", 4); ?>
	<? else: ?>
		<? tab_bar_main("?action=main"); ?>
	<? endif; ?>
	<? include_once("notifications.php"); ?>

	<div data-role="content" style="text-align: center;">
		<br />
		<br />
		<div class="ui-grid-b">
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) { ?>
				<?php if ($status == "on") { ?>
					<div class="ui-block-<?= $column ?>">
						<a href="/<?= isset($this->applicationUrls[$applicationName])?$this->applicationUrls[$applicationName]:$applicationName ?>" rel="external" class="myIcon"><img
							alt="<?= $applicationName ?>"
							src="../../<?= $applicationName ?>/img/icon.png" width="50px"></a>
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

<? include_once 'footer.php'; ?>
