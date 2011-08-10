<div class="menu">
	<ul>
		<li><a href="<?=ROOTPATH.'application/'.$serviceName?><?=isset($_GET['template'])?'?template='.$_GET['template']:''?>">rechercher</a></li>
<?php 	if(USER_CONNECTED):?>
		<li><a href="<?=ROOTPATH.'application/'.$serviceName?>/publish<?=isset($_GET['template'])?'?template='.$_GET['template']:''?>">publier</a></li>
<?php 	else:?>
		<li>publier</li>
<?php 	endif?>
	</ul>
</div>
