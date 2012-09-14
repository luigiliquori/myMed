<? include("header.php"); ?>

<? if(empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>


<div id="home" data-role="page">

	<div data-role="header" data-theme="d" data-position="fixed">
		<? tab_bar_main("#home") ?>
		<? include("notifications.php"); ?>
	</div>

	<div data-role="content" style="text-align: center;">
		<br />
		
		<div class="ui-grid-b" Style="padding: 10px;">
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
<? include("StoreView.php"); ?>

<? include("footer.php"); ?>