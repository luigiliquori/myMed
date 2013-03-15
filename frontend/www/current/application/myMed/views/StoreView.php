<?php

require_once("header.php");
define('STORE_PREFIX' , 'store_');

?>

<div data-role="page" id="store" data-dom-cache="true">
	<? if ($_SESSION['user']->is_guest): ?>
		<? tab_bar_main("?action=store", 6); ?>
	<? else: ?>
		<? tab_bar_main("?action=store"); ?>
	<? endif; ?>
	<? include("notifications.php"); ?>

	<div data-role="content" Style="padding: 0px; opacity: 0.9">
		<div class="ui-grid-a" style="position: relative;">
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) {  ?>
					<div class="ui-block-<?= $column ?>">
						<div class="ui-bar-c" style="height:120px; text-align: justify; overflow: hidden; ">
							<a href="?action=store&applicationStore=<?= $applicationName ?>" rel="external" class="myIcon" style="text-decoration: none;">
								
								<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" style="height:50px;position: relative; left:15px; top:15px;">
								
								<div Style="position: relative; left:7px; top:15px;">
							    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							    		<?php if($i*20-20 < $_SESSION['reputation'][STORE_PREFIX . $applicationName] ) { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
							    		<?php } else { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
							    		<?php } ?>
							    	<? } ?>
						    	</div>
						    	
						    	<? if($_SESSION['applicationList'][$applicationName]=='on'): ?>
									<p style="color: green; font-size: 9pt;  margin:15px; float:right; " >
										Installed
									</p>	
									<?else:?>
									<p style="color: gray; font-size: 9pt; float:right;  margin:15px;" >
										Not installed
									</p>
									<?endif?>
								
								<div style="position: relative; font-size: 9pt; font-weight: bold; left: 100px; top:-70px; width: 50%; ">
									<br/>
									<?= $applicationName ?>
									<p style="color: black; font-size: 7pt; bottom:10px; overflow: hidden;text-overflow: ellipsis; " >
										<?php @include (MYMED_ROOT . "/application/" . $applicationName . "/doc/description.php") ?>
									</p>
								</div>
								
							</a>
						</div>
					</div>
					<?php 
			    	if($column == "a") {
			    		$column = "b";
			    	} else if($column == "b") {
			    		$column = "a";
			    	}
			} ?>
		</div>
			
	</div>

</div>

<? include_once 'footer.php'; ?>
