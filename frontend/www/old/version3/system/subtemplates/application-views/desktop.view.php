<?php
/**
 * @param array<string>	$tabList
 * @param int	$selectedTab
 */
$mobileTabWidth	= 100/count($tabList);
$tabFocusId	= 0;
?>
				<div id="desktop<?=$selectedTab?>" class="desktop focus">
<?php foreach($tabList[$selectedTab] as $colId => $column):?>
					<div class="column col<?=$colId?>">
<?php 	foreach($column['appList'] as $app):?>
						<div class="window" id="window_<?=$app?>">
							<div class="title"><a href="application/<?=$app?>"><img src="<?=ROOTPATH?>services/<?=$app?>/icon.png" alt="" /><?=$app?></a></div>
						</div>
<?php 	endforeach;?>
					</div>
<?php endforeach;?>
					<div class="glassPane"></div>
				</div>
