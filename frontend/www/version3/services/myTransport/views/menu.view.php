<div class="menu">
	<ul>
		<li><a href="<?=ROOTPATH.'application/'.$serviceName?>">rechercher</a></li>
<?php 	if(USER_CONNECTED):?>
		<li><a href="<?=ROOTPATH.'application/'.$serviceName?>/publish">publier</a></li>
<?php 	else:?>
		<li>publier</li>
<?php 	endif?>
	</ul>
</div>
