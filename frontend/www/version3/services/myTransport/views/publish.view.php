<?php if(!isset($_SESSION['myTransport_publish_info'])):?>
<?php require __DIR__.'/menu.view.php';?>
<form method="post" action="<?=isset($_GET['template'])?'?template='.$_GET['template']:''?>">
	<input name="code" type="hidden" value="search"/>
	<div>
		<div>
			<label for="from2">Ville de départ</label>
			<input id="from2" name="from" type="text" />
		</div>
		<div>
			<label for="to2">Ville d'arrivée</label>
			<input id="to2" name="to" type="text" />
		</div>
		<div>
			<label for="theDate">Date</label>
			<input id="theDate" name="theDate" type="date" value="<?php echo date('Y-m-d');?>" />
		</div>
		<div>
			<label for="info">Informations</label>
			<textarea style="width: 180px;" id="info" ></textarea>
		</div>
	</div>
	<button type="submit">Publier</button>
</form>
<?php else:?>
<a href="<?=ROOTPATH.'application/'.$serviceName?><?=isset($_GET['template'])?'?template='.$_GET['template']:''?>" class="button">Retour</a>
<?=$_SESSION['myTransport_publish_info']?>
<?php endif;?>
