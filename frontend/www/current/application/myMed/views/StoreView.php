<? include("header.php"); ?>

<? define('STORE_PREFIX' , '_store'); ?>

<div data-role="page">

	<? tab_bar_main("?action=store"); ?>
	<? include("notifications.php"); ?>

	<div data-role="content" Style="padding: 0px; opacity: 0.9">
		
		<div class="ui-grid-a" style="position: relative;">
			<?php $column = "a"; ?>
			<?php foreach ($_SESSION['applicationList'] as $applicationName => $status) {  ?>
					<div class="ui-block-<?= $column ?>">
						<div class="ui-bar-c" style="height:120px; text-align: left; overflow: hidden;">
							<a href="?action=store&applicationStore=<?= $applicationName ?>" rel="external" class="myIcon" style="text-decoration: none;">
								
								<img alt="<?= $applicationName ?>" src="../../application/<?= $applicationName ?>/img/icon.png" width="50px" Style="position: relative; left:8px; top:5px;">
								
								<div Style="position: relative; left: 0px; top: 0px;">
							    	<?php for($i=1 ; $i <= 5 ; $i++) { ?>
							    		<?php if($i*20-20 < $_SESSION['reputation'][$applicationName . STORE_PREFIX] ) { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/yellowStar.png" width="10" Style="left: <?= $i ?>0px;" />
							    		<?php } else { ?>
							    			<img alt="rep" src="<?= APP_ROOT ?>/img/grayStar.png" width="10" Style="left: <?= $i ?>0px;"/>
							    		<?php } ?>
							    	<? } ?>
						    	</div>
								
								<div style="position: relative; font-size: 9pt; font-weight: bold; left: 80px; top:-70px; width: 50%; ">
									<?= $applicationName ?>
									<p style="color: black; font-size: 7pt; " >
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

<? include("footer.php"); ?>
