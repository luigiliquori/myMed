<?php require_once("header.php");?>

<div id="home" data-role="page" data-dom-cache="true">
	
	<? if ($_SESSION['user']->is_guest): ?>
		<? tab_bar_main("?action=main", 6); ?>
	<? else: ?>
		<? tab_bar_main("?action=main"); ?>
	<? endif; ?>
	<? include_once("notifications.php"); ?>

	<div data-role="content" style="text-align: center;">
		<br />
		<br />
		<div class="ui-grid-b">
			<?php // Check for installed applications 
				  $app_installed=0;
				  foreach ($_SESSION['applicationList'] as $applicationName => $status) { 
				  	if ($status == "on")
				  		$app_installed = $app_installed + 1; 
				  }
				  
				  if($app_installed == 0)
				  	echo "<br/><strong>"._("You don't have any application installed! Please choose some from the store.")."</strong>";
			?>
			
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) { ?>
				<?php if ($status == "on") { ?>
					<div class="ui-block-<?= $column ?>">
						<a href="/<?= isset($this->applicationUrls[$applicationName])?$this->applicationUrls[$applicationName]:$applicationName ?>" rel="external" class="myIcon"><img
							alt="<?= $applicationName ?>"
							src="../../application/<?= $applicationName ?>/img/icon.png" style="height: 50px;"></a>
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
