<?php 
/**
 * @param Profile $profile	profile to print
 */
?>
				<div id="profile">
					<h2><?=$profile->gender=='M'?'M.':'Mme.'?> <?=$profile->firstName?> <?=$profile->lastName?></h2>
					<div>Né<?=$profile->gender=='M'?'':'e'?> le&nbsp;: <?=date('d/m/Y', strtotime($profile->birthday))?></div>
					<div>Ville&nbsp;: <?=$profile->hometown?></div>
				</div>
