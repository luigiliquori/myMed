<?php if(!isset($res)):?>
<?php require __DIR__.'/menu.view.php';?>
<form method="get" action="">
	<div>
		<div>
			<label for="from1">Ville de départ</label>
			<input id="from1" name="from" type="text" />
		</div>
		<div>
			<label for="to1">Ville d'arrivée</label>
			<input id="to1" name="to" type="text" />
		</div>
		<div>
			<label for="theDate">Date</label>
			<input id="theDate" name="theDate" type="date" value="<?php echo date('Y-m-d');?>" />
		</div>
	</div>
	<button type="submit">Rechercher</button>
</form>
<?php else:?>
<a href="<?=ROOTPATH.'application/'.$serviceName?>" class="button">Retour</a>
<h2>Résultats :</h2>
<?php 	if($res==null):?>
<p>Trajet introuvable</p>
<?php 	else:?>
<div class="result">
	<img width="200px" alt="profile picture" src="<?= $res->profilePicture ?>" />
	<div>
		<h1><?= $res->name ?></h1>
		<ul>
			<li><?= $res->gender=='F'?'Femme':'Homme' ?></li>
			<li><?= $res->birthday ?></li>
		</ul>
	</div>
</div>
<?php 	endif;?>
<?php endif;?>
