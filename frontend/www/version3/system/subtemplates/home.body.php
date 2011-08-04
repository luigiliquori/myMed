<span class="vertiAligner"></span><!--
--><div id="description_myMed">
	<img alt="Un méta réseau social pour partager des applications" src="<?=ROOTPATH?>style/img/entonnoire.png" />
	<h2 class="title">myMed se joint à votre réseau social préféré pour ajouter de nouvelles fonctionnalités&#160;!</h2>
</div><!--
--><div id="login_subscribe">
	<div class="login">
		<h2>Connectez-vous avec votre réseau social préféré&#160;:</h2>
		<div class="buttonList">
			<div class="main">
				<?php GlobalConnexion::getInstance()->mainButtons();?>
			</div>
			<div class="minor">
				<?php GlobalConnexion::getInstance()->minorButtons();?>
			</div>
		</div>
	</div>
	<div class="subscribe">
		<h2 class="title">
			Vous n'avez pas encore de profile sur un réseau social&#160;?<br />
			myMed en fourni un pour vous&#160;!
		</h2>
		<form action="#" method="post">
			<div>
				<div><label for="nom">Nom</label><input type="text" name="nom" id="nom" /></div>
				<div><label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom" /></div>
				<div><label for="email">Courriel</label><input type="email" name="email" id="email" /></div>
				<div><label for="password">Mot de passe</label><input type="password" name="password" id="password" /></div>
				<div><label for="confirm">Confirmation</label><input type="password" name="confirm" id="confirm" /></div>
				<div>
					<label for="gender">Je suis</label>
					<select name="gender" id="gender">
						<option value="Homme" default="default">Homme</option>
						<option value="Femme">Femme</option>
						<option value="Autre">Autre</option>
					</select>
				</div>
			</div>
			<button type="submit"><span>inscription</span></button>
		</form>
	</div>
</div> 
